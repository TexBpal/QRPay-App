<?php

namespace App\Http\Controllers\Api\User;

use App\Constants\GlobalConst;
use App\Constants\NotificationConst;
use App\Constants\PaymentGatewayConst;
use App\Http\Controllers\Controller;
use App\Http\Helpers\Api\Helpers;
use App\Http\Helpers\NotificationHelper;
use App\Models\Admin\BasicSettings;
use App\Models\Admin\Currency;
use App\Models\Admin\TransactionSetting;
use App\Models\StripeVirtualCard;
use App\Models\Transaction;
use App\Models\UserNotification;
use App\Models\UserWallet;
use App\Models\VirtualCardApi;
use App\Notifications\Admin\ActivityNotification;
use App\Notifications\User\VirtualCard\CreateMail;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Helpers\PushNotificationHelper;
use App\Http\Helpers\TransactionLimit;
use App\Models\Admin\ExchangeRate;
use App\Providers\Admin\BasicSettingsProvider;

class StripeVirtualController extends Controller
{
    protected $api;
    protected $card_limit;
    protected $basic_settings;
    protected $stripe_supported_currencies;

    public function __construct()
    {
        $cardApi = VirtualCardApi::first();
        $this->api =  $cardApi;
        $this->card_limit =  $cardApi->card_limit;
        $this->basic_settings = BasicSettingsProvider::get();
        $this->stripe_supported_currencies = [
            "currencies" => [ "USD","GBP", "EUR"],
            "countries" => ["United States","United Kingdom", "Austria"]
        ];
    }
    public function index()
    {
        $user = auth()->user();
        $basic_settings = BasicSettings::first();
        $card_mode = $this->api->config->stripe_mode == GlobalConst::SANDBOX ? GlobalConst::SANDBOX : GlobalConst::LIVE;
        $card_basic_info = [
            'card_create_limit' => @$this->api->card_limit,
            'card_back_details' => @$this->api->card_details,
            'card_bg'           => get_image(@$this->api->image,'card-api'),
            'site_title'        => @$basic_settings->site_name,
            'site_logo'         => get_logo(@$basic_settings,'dark'),
            'site_fav'          => get_fav($basic_settings,'dark'),
        ];
        $myCards = StripeVirtualCard::where('user_id',$user->id)->where('mode',$card_mode)->latest()->take($this->card_limit)->get()->map(function($data){
            $basic_settings = BasicSettings::first();
            $statusInfo = [
                "active" =>      1,
                "inactive" =>     0,
                ];
            return[
                'id'                => $data->id,
                'card_id'           => $data->card_id,
                'card_mode'         => $data->mode,
                'currency'          => strtoupper($data->currency),
                'amount'            => getAmount($data->amount,get_wallet_precision()),
                'card_holder'       => $data->name,
                'brand'             => $data->brand,
                'type'              => $data->type,
                'card_pan'          => $data->maskedPan,
                'expiry_month'      => $data->expiryMonth,
                'expiry_year'       => $data->expiryYear,
                'cvv'               => "***",
                'card_back_details' => @$this->api->card_details,
                'site_title'        => @$basic_settings->site_name,
                'site_logo'         => get_logo(@$basic_settings,'dark'),
                'status'            => $data->status,
                'is_default'        => $data->is_default,
                'status_info'       => (object)$statusInfo,
            ];
        });
        $totalCards = StripeVirtualCard::where('user_id',auth()->user()->id)->where('mode',$card_mode)->count();
        $cardCharge = TransactionSetting::where('slug','virtual_card')->where('status',1)->get()->map(function($data){
            return [
                'id'                        => $data->id,
                'slug'                      => $data->slug,
                'title'                     => $data->title,
                'fixed_charge'              => getAmount($data->fixed_charge,get_wallet_precision()),
                'percent_charge'            => getAmount($data->percent_charge,get_wallet_precision()),
                'min_limit'                 => getAmount($data->min_limit,get_wallet_precision()),
                'daily_limit'               => getAmount($data->daily_limit,get_wallet_precision()),
                'max_limit'                 => get_amount($data->max_limit,get_wallet_precision()),
                'monthly_limit'             => getAmount($data->monthly_limit,get_wallet_precision()),
            ];
        })->first();
        $transactions = Transaction::auth()->virtualCard()->latest()->take(10)->get()->map(function($item){
            $statusInfo = [
                "success" =>      1,
                "pending" =>      2,
                "rejected" =>     3,
            ];
            $card_currency = $item->details->card_info->currency ?? get_default_currency_code();
            return[
                'id' => $item->id,
                'trx' => $item->trx_id,
                'transaction_type' => "Virtual Card".'('. @$item->remark.')',
                'request_amount' => getAmount($item->request_amount, get_wallet_precision()).' '.$card_currency ,
                'payable' => getAmount($item->payable, get_wallet_precision()).' '.get_default_currency_code(),
                'total_charge' => getAmount($item->charge->total_charge, get_wallet_precision()).' '.get_default_currency_code(),
                'card_amount' => getAmount(@$item->details->card_info->amount, get_wallet_precision()).' '.$card_currency,
                'card_number' => $item->details->card_info->card_pan??$item->details->card_info->maskedPan??$item->details->card_info->card_number??"",
                'current_balance' => getAmount($item->available_balance, get_wallet_precision()).' '.get_default_currency_code(),
                'status' => $item->stringStatus->value ,
                'date_time' => $item->created_at ,
                'status_info' =>(object)$statusInfo ,

            ];
        });
        $userWallet = $userWallet =   user_wallets(authGuardApi(),'user_id');

        $supported_currency = support_currencies($this->stripe_supported_currencies['currencies'],$this->stripe_supported_currencies['countries']);

        $get_remaining_fields = [
            'transaction_type'  =>  PaymentGatewayConst::VIRTUALCARD,
            'attribute'         =>  PaymentGatewayConst::RECEIVED,
        ];

        $data =[
            'base_curr'             => get_default_currency_code(),
            'base_curr_rate'        => getAmount(get_default_currency_rate(),get_wallet_precision()),
            'get_remaining_fields'  => (object) $get_remaining_fields,
            'supported_currency'    => $supported_currency,
            'card_create_action'    => $totalCards <  $this->card_limit ? true : false,
            'card_basic_info'       => (object) $card_basic_info,
            'myCard'                => $myCards,
            'userWallet'            => $userWallet,
            'cardCharge'            => (object)$cardCharge,
            'transactions'          => $transactions,
        ];
        $message =  ['success'=>[__('Virtual Card Stripe')]];
        return Helpers::success($data,$message);
    }
    public function cardDetails(){
        $validator = Validator::make(request()->all(), [
            'card_id'     => "required|string",
        ]);
        if($validator->fails()){
            $error =  ['error'=>$validator->errors()->all()];
            return Helpers::validation($error);
        }
        $card_id = request()->card_id;
        $user = auth()->user();
        $myCard = StripeVirtualCard::where('user_id',$user->id)->where('card_id',$card_id)->first();
        if(!$myCard){
            $error = ['error'=>[__('Something is wrong in your card')]];
            return Helpers::error($error);
        }
        $myCards = StripeVirtualCard::where('card_id',$card_id)->where('user_id',$user->id)->get()->map(function($data){
            $basic_settings = BasicSettings::first();
            $statusInfo = [
                "active" =>      1,
                "inactive" =>     0,
                ];

            return[
                'id' => $data->id,
                'card_id' => $data->card_id,
                'currency' => $data->currency,
                'card_holder' => $data->name,
                'brand' => $data->brand,
                'type' => $data->type,
                'card_pan' => $data->maskedPan,
                'expiry_month' => $data->expiryMonth,
                'expiry_year' => $data->expiryYear,
                'cvv' => "***",
                'card_back_details' => @$this->api->card_details,
                'site_title' =>@$basic_settings->site_name,
                'site_logo' =>get_logo(@$basic_settings,'dark'),
                'site_fav' =>get_fav($basic_settings,'dark'),
                'status' => $data->status,
                'is_default' => $data->is_default,
                'status_info' =>(object)$statusInfo ,
            ];
        })->first();
        $data =[
            'base_curr' => get_default_currency_code(),
            'card_details'=> $myCards,
        ];
        $message =  ['success'=>[__('card Details')]];
        return Helpers::success($data,$message);
    }
    public function makeDefaultOrRemove(Request $request) {
        $validator = Validator::make($request->all(), [
            'card_id'     => "required|string",
        ]);
        if($validator->fails()){
            $error =  ['error'=>$validator->errors()->all()];
            return Helpers::validation($error);
        }
        $validated = $validator->validate();
        $user = auth()->user();
        $targetCard =  StripeVirtualCard::where('card_id',$validated['card_id'])->where('user_id',$user->id)->first();
        if(!$targetCard){
            $error = ['error'=>[__('Something is wrong in your card')]];
            return Helpers::error($error);
        };
        $withOutTargetCards =  StripeVirtualCard::where('id','!=',$targetCard->id)->where('user_id',$user->id)->get();
        try{
            $targetCard->update([
                'is_default'         => $targetCard->is_default ? 0 : 1,
            ]);
            if(isset(  $withOutTargetCards)){
                foreach(  $withOutTargetCards as $card){
                    $card->is_default = false;
                    $card->save();
                }
            }
            $message =  ['success'=>[__('Status Updated Successfully!')]];
            return Helpers::onlysuccess($message);

        }catch(Exception $e) {
            $error = ['error'=>[__("Something went wrong! Please try again.")]];
            return Helpers::error($error);
        }
    }
    public function cardTransaction() {
        $validator = Validator::make(request()->all(), [
            'card_id'     => "required|string",
        ]);
        if($validator->fails()){
            $error =  ['error'=>$validator->errors()->all()];
            return Helpers::validation($error);
        }
        $card_id = request()->card_id;
        $user = auth()->user();
        $card = StripeVirtualCard::where('user_id',$user->id)->where('card_id',$card_id)->first();
        if(!$card){
            $error = ['error'=>[__('Something is wrong in your card')]];
            return Helpers::error($error);
        }
        $card_truns =   getStripeCardTransactions($card->card_id);
        $cardTransactions = collect($card_truns['data'])->map(function ($transaction) {
            $card_id = request()->card_id;
            $user = auth()->user();
            $card = StripeVirtualCard::where('user_id',$user->id)->where('card_id',$card_id)->first();
            return [
                'id' => $transaction['id'],
                'amount' => $transaction['amount']/100,
                'currency' => $transaction['currency'],
                'type' => $transaction['type'],
                'card_number' =>"....". $card->last4,
                'card_holder' =>$card->name,
                'descriptions' =>$transaction['merchant_data']->name,
            ];
        });
        $data = [
            'cardTransactions' => $cardTransactions
        ];

        $message = ['success' => [__("Virtual Card Transaction")]];
        return Helpers::success($data, $message);


    }
    public function getSensitiveData(Request $request){
        $validator = Validator::make($request->all(), [
            'card_id'     => "required|string",
        ]);
        if($validator->fails()){
            $error =  ['error'=>$validator->errors()->all()];
            return Helpers::validation($error);
        }
        $validated = $validator->validate();
        $user = auth()->user();
        $targetCard =  StripeVirtualCard::where('card_id',$validated['card_id'])->where('user_id',$user->id)->first();
        if(!$targetCard){
            $error = ['error'=>[__('Something is wrong in your card')]];
            return Helpers::error($error);
        };
        $result = getSensitiveData( $targetCard->card_id);

        $data =[
            'sensitive_data' => $result,
        ];
        $message =  ['success'=>[__('Virtual Card Sensitive Data')]];
        return Helpers::success($data,$message);
    }
    public function cardInactive(Request $request){
        $validator = Validator::make($request->all(), [
            'card_id'     => "required|string",
        ]);
        if($validator->fails()){
            $error =  ['error'=>$validator->errors()->all()];
            return Helpers::validation($error);
        }
        $card_id = $request->card_id;
        $user = auth()->user();
        $status = 'inactive';
        $card = StripeVirtualCard::where('user_id',$user->id)->where('card_id',$card_id)->first();
        if(!$card){
            $error = ['error'=>[__('Something is wrong in your card')]];
            return Helpers::error($error);
        }
        if($card->status == false){
            $error = ['error'=>[__('Sorry,This Card Is Already Inactive')]];
            return Helpers::error($error);
        }
        $result = cardActiveInactive($card->card_id,$status);
        if(isset($result['status'])){
            if($result['status'] == true){
                $card->status = false;
                $card->save();
                $message =  ['success'=>[__('Card Inactive Successfully')]];
                return Helpers::onlysuccess($message);
            }elseif($result['status'] == false){
                $error = ['error'=>[$result['message']??"Something Is Wrong"]];
                return Helpers::error($error);
            }
        }

    }
    public function cardActive(Request $request){
        $validator = Validator::make($request->all(), [
            'card_id'     => "required|string",
        ]);
        if($validator->fails()){
            $error =  ['error'=>$validator->errors()->all()];
            return Helpers::validation($error);
        }
        $card_id = $request->card_id;
        $user = auth()->user();
        $status = 'active';
        $card = StripeVirtualCard::where('user_id',$user->id)->where('card_id',$card_id)->first();
        if(!$card){
            $error = ['error'=>[__('Sorry,This Card Is Already Inactive')]];
            return Helpers::error($error);
        }
        if($card->status == true){
            $error = ['error'=>[__('Sorry,This Card Is Already Active')]];
            return Helpers::error($error);
        }
        $result = cardActiveInactive($card->card_id,$status);
        if(isset($result['status'])){
            if($result['status'] == true){
                $card->status = true;
                $card->save();
                $message =  ['success'=>[__('Card Active Successfully')]];
                return Helpers::onlysuccess($message);
            }elseif($result['status'] == false){
                $error = ['error'=>[$result['message']??"Something Is Wrong"]];
                return Helpers::error($error);
            }
        }

    }
    public function cardBuy(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'card_amount' => 'required|numeric|gt:0',
            'currency'          => "required|string",
            'from_currency'     => "required|string|exists:currencies,code",
        ]);
        if($validator->fails()){
            $error =  ['error'=>$validator->errors()->all()];
            return Helpers::validation($error);
        }

        $validated = $validator->validate();
        $basic_setting = BasicSettings::first();
        $user = auth()->user();
        $amount = $request->card_amount;

        $totalCards = StripeVirtualCard::where('user_id',auth()->user()->id)->count();
        if($totalCards >= $this->card_limit){
            $error = ['error'=>["Sorry! You can not create more than ".$this->card_limit ." card using the same email address."]];
            return Helpers::error($error);
        }

        $wallet = UserWallet::where('user_id',$user->id)->whereHas("currency",function($q) use ($validated) {
            $q->where("code",$validated['from_currency'])->active();
        })->active()->first();
        if(!$wallet){
            $error = ['error'=>[__('User wallet not found')]];
            return Helpers::error($error);
        }
        $card_currency = ExchangeRate::active()->where('currency_code',$validated['currency'])->first();
        if(!$card_currency){
            $error = ['error'=>[__('Card Currency Not Found')]];
            return Helpers::error($error);
        }

        $cardCharge = TransactionSetting::where('slug','virtual_card')->where('status',1)->first();
        $charges = $this->cardCharges($validated['card_amount'],$cardCharge,$wallet,$card_currency);
        $minLimit =  $cardCharge->min_limit *  $charges['card_currency_rate'];
        $maxLimit =  $cardCharge->max_limit *  $charges['card_currency_rate'];

        if($amount < $minLimit || $amount > $maxLimit){
            $error = ['error'=>[__('Please follow the transaction limit')]];
            return Helpers::error($error);
        }

        //daily and monthly
        try{
            (new TransactionLimit())->trxLimit('user_id',$wallet->user->id,PaymentGatewayConst::VIRTUALCARD,$wallet->currency,$amount,$cardCharge,PaymentGatewayConst::RECEIVED,null,$charges['exchange_rate']);
        }catch(Exception $e){
            $errorData = json_decode($e->getMessage(), true);
            $error = ['error'=>[__($errorData['message']??"")]];
            return Helpers::error($error);
        }
        if($charges['payable'] > $wallet->balance){
            $error = ['error'=>[__('Sorry, insufficient balance')]];
            return Helpers::error($error);
        }
        $card_mode = $this->api->config->stripe_mode == GlobalConst::SANDBOX ? GlobalConst::SANDBOX : GlobalConst::LIVE;
        //***************************Started Test Mode Card***************************** */
        if( $card_mode == GlobalConst::SANDBOX){
            $created_card = null;
            try{
                $created_card =  $this->create_test_mode_card( $validated, $user);
            }catch(Exception $e){
                $error = ['error'=>[$e->getMessage()]];
                return Helpers::error($error);
            }
        //***************************Started LIVE Mode Card***************************** */
        }else{
            try{
                $created_card =  $this->create_live_mode_card( $validated, $user);
            }catch(Exception $e){
                $error = ['error'=>[$e->getMessage()]];
                return Helpers::error($error);
            }
        }

       if($created_card['status']  = true){
            $card_info = (object)$created_card['data'];
            $v_card = new StripeVirtualCard();
            $v_card->user_id = $user->id;
            $v_card->name = $user->fullname;
            $v_card->card_id = $card_info->id;
            $v_card->type = $card_info->type;
            $v_card->brand = $card_info->brand;
            $v_card->currency = $card_info->currency;
            $v_card->amount = $amount;
            $v_card->charge = $charges['total_charge'];
            $v_card->maskedPan = "0000********".$card_info->last4;
            $v_card->last4 = $card_info->last4;
            $v_card->expiryMonth = $card_info->exp_month;
            $v_card->expiryYear = $card_info->exp_year;
            $v_card->status = true;
            $v_card->card_details = $card_info;
            $v_card->save();

            $trx_id =  'CB'.getTrxNum();

            try{
                $sender = $this->insertCardBuy($trx_id,$user,$wallet,$amount,$v_card,$charges);
                $this->insertBuyCardCharge($charges,$user,$sender,$v_card->maskedPan);
                try{
                    if( $basic_setting->email_notification == true){
                        $notifyDataSender = [
                            'trx_id'            => $trx_id,
                            'title'             => __("Virtual Card (Buy Card)"),
                            'request_amount'    => get_amount($amount,$charges['card_currency'],$charges['precision_digit']),
                            'payable'           =>  get_amount($charges['payable'],$charges['from_currency'],$charges['precision_digit']),
                            'charges'           => get_amount( $charges['total_charge'],$charges['from_currency'],$charges['precision_digit']),
                            'card_amount'       => get_amount($amount,$charges['card_currency'],$charges['precision_digit']),
                            'card_pan'          => $v_card->maskedPan,
                            'status'            => __("success"),
                          ];
                        $user->notify(new CreateMail($user,(object)$notifyDataSender));
                    }
                }catch(Exception $e){}

                 //admin notification
                $this->adminNotification($trx_id,$charges,$amount,$user,$v_card);
                $message =  ['success'=>[__('Virtual Card Buy Successfully')]];
                return Helpers::onlysuccess($message);
            }catch(Exception $e){
                $error = ['error'=>[__("Something went wrong! Please try again.")]];
                return Helpers::error($error);
            }

       }

    }
    public function create_live_mode_card($request_data,$user){

        //  create connected account
        if($user->stripe_connected_account == null){
            $c_account =  createConnectAccount($request_data);
            if( isset($c_account['status'])){
                if($c_account['status'] == false){
                    throw new Exception($c_account['message']);
                }
            }
            $stripe_connected_account_data = (object) $c_account['data'] ?? [];
            $user->stripe_connected_account = $stripe_connected_account_data;
            $user->save();
            $c_account = $user->stripe_connected_account->id;

        }else{
            $c_account = $user->stripe_connected_account->id;
        }
        //  create financial account
        if($user->stripe_financial_account == null){
            $f_account =  createFinancialAccount($request_data,$c_account);

            if( isset($f_account['status'])){
                if($f_account['status'] == false){
                    throw new Exception($f_account['message']);
                }
            }
            $stripe_financial_account_data = (object) $f_account['data'] ?? [];
            $user->stripe_financial_account = $stripe_financial_account_data;
            $user->save();
            $f_account = $user->stripe_financial_account->id;
        }else{
            $f_account = $user->stripe_financial_account->id;
        }


        //create card holder
        if( $user->stripe_card_holders == null){
        $card_holder =  createCardHolders($user,$c_account,$request_data);

        if( isset($card_holder['status'])){
            if($card_holder['status'] == false){
                throw new Exception($card_holder['message']);
            }
        }

        $stripe_card_holders_data = (object)$card_holder['data'];

        $user->stripe_card_holders =   (object)$stripe_card_holders_data;
        $user->save();
        $card_holder_id = $user->stripe_card_holders->id;

        }else{
        $card_holder_id = $user->stripe_card_holders->id;
        }


        //account update
        $account_update = updateAccount($user,$c_account,$request_data);
        if(isset($account_update['status'])){
            if($account_update['status'] == false){
                throw new Exception($account_update['message']);
            }
        }


        //create card now
        $created_card = createVirtualCard($card_holder_id,$c_account,$f_account,$request_data);
        if(isset($created_card['status'])){
            if($created_card['status'] == false){
                throw new Exception($created_card['message']);
            }
        }

        //now funded amount
        $funded_amount = transfer($request_data,$c_account);
        if(isset($funded_amount['status'])){
            if($funded_amount['status'] == false){
                throw new Exception($funded_amount['message']);
            }
        }
        return  $created_card ?? null;
    }
    public function create_test_mode_card($request_data,$user){

        if( $user->stripe_test_card_holder == null){
            $card_holder =  test_cardHolder($user,$request_data);
            if( isset($card_holder['status'])){
                if($card_holder['status'] == false){
                    throw new Exception($card_holder['message']);
                }
            }

            $stripe_card_holders_data = (object)$card_holder['data'];
            $user->stripe_test_card_holder =   (object)$stripe_card_holders_data;
            $user->save();
            $card_holder_id = $user->stripe_test_card_holder->id;

        }else{
            $card_holder_id = $user->stripe_test_card_holder->id;
        }

        $created_card =  create_test_card($card_holder_id,$request_data);
        if(isset($created_card['status'])){
                if($created_card['status'] == false){
                throw new Exception($created_card['message']);
                }
        }
        return  $created_card ?? null;

    }
    //card buy helper
    public function insertCardBuy($trx_id,$user,$wallet,$amount,$v_card,$charges) {
        $trx_id = $trx_id;
        $authWallet = $wallet;
        $afterCharge = ($authWallet->balance - $charges['payable']);
        $details =[
            'card_info' =>   $v_card??'',
            'charges'   =>   $charges,
        ];
        DB::beginTransaction();
        try{
            $id = DB::table("transactions")->insertGetId([
                'user_id'                       => $user->id,
                'user_wallet_id'                => $authWallet->id,
                'payment_gateway_currency_id'   => null,
                'type'                          => PaymentGatewayConst::VIRTUALCARD,
                'trx_id'                        => $trx_id,
                'request_amount'                => $amount,
                'payable'                       => $charges['payable'],
                'available_balance'             => $afterCharge,
                'remark'                        => PaymentGatewayConst::CARDBUY,
                'details'                       => json_encode($details),
                'attribute'                      =>PaymentGatewayConst::RECEIVED,
                'status'                        => true,
                'created_at'                    => now(),
            ]);
            $this->updateSenderWalletBalance($authWallet,$afterCharge);

            DB::commit();
        }catch(Exception $e) {
            DB::rollBack();
            $error = ['error'=>[__("Something went wrong! Please try again.")]];
            return Helpers::error($error);
        }
        return $id;
    }
    public function insertBuyCardCharge($charges,$user,$id,$masked_card) {
        DB::beginTransaction();
        try{
            DB::table('transaction_charges')->insert([
                'transaction_id'    => $id,
                'percent_charge'    => $charges['percent_charge'],
                'fixed_charge'      => $charges['fixed_charge'],
                'total_charge'      => $charges['total_charge'],
                'created_at'        => now(),
            ]);
            DB::commit();

            //notification
            $notification_content = [
                'title'         =>__('buy Card'),
                'message'       => __('Buy card successful')." ".$masked_card,
                'image'         => files_asset_path('profile-default'),
            ];

            UserNotification::create([
                'type'      => NotificationConst::CARD_BUY,
                'user_id'  => $user->id,
                'message'   => $notification_content,
            ]);
            //Push Notifications
            if( $this->basic_settings->push_notification == true){
                try{
                        (new PushNotificationHelper())->prepareApi([$user->id],[
                            'title' => $notification_content['title'],
                            'desc'  => $notification_content['message'],
                            'user_type' => 'user',
                        ])->send();
                }catch(Exception $e) {}
            }
            DB::commit();
        }catch(Exception $e) {
            DB::rollBack();
            $error = ['error'=>[__("Something went wrong! Please try again.")]];
            return Helpers::error($error);
        }
    }
    //update user balance
    public function updateSenderWalletBalance($authWallet,$afterCharge) {
        $authWallet->update([
            'balance'   => $afterCharge,
        ]);
    }

    //admin notification
    public function adminNotification($trx_id,$charges,$amount,$user,$v_card){
        $notification_content = [
            //email notification
            'subject' => __("Virtual Card (Buy Card)"),
            'greeting' => __("Virtual Card Information"),
            'email_content' =>__("web_trx_id")." : ".$trx_id."<br>".__("request Amount")." : ".get_amount($amount,$charges['card_currency'],$charges['precision_digit'])."<br>".__("Fees & Charges")." : ".get_amount($charges['total_charge'],$charges['from_currency'],$charges['precision_digit'])."<br>".__("Total Payable Amount")." : ".get_amount($charges['payable'],$charges['from_currency'],$charges['precision_digit'])."<br>".__("card Masked")." : ".@$v_card->maskedPan."<br>".__("Status")." : ".__("success"),

            //push notification
            'push_title' => __("Virtual Card (Buy Card)")." (".userGuard()['type'].")",
            'push_content' => __('web_trx_id')." : ".$trx_id." ".__("request Amount")." : ".get_amount($amount,$charges['card_currency'],$charges['precision_digit'])." ".__("card Masked")." : ".$v_card->maskedPan??"",

            //admin db notification
            'notification_type' =>  NotificationConst::CARD_BUY,
            'admin_db_title' => "Virtual Card Buy"." (".userGuard()['type'].")",
            'admin_db_message' => "Transaction ID"." : ".$trx_id.",".__("Request Amount")." : ".get_amount($amount,$charges['card_currency'],$charges['precision_digit']).","."Card Masked"." : ".@$v_card->maskedPan." (".$user->email.")",
        ];

        try{
            //notification
            (new NotificationHelper())->admin(['admin.virtual.card.logs','admin.virtual.card.export.data'])
                                    ->mail(ActivityNotification::class, [
                                        'subject'   => $notification_content['subject'],
                                        'greeting'  => $notification_content['greeting'],
                                        'content'   => $notification_content['email_content'],
                                    ])
                                    ->push([
                                        'user_type' => "admin",
                                        'title' => $notification_content['push_title'],
                                        'desc'  => $notification_content['push_content'],
                                    ])
                                    ->adminDbContent([
                                        'type' => $notification_content['notification_type'],
                                        'title' => $notification_content['admin_db_title'],
                                        'message'  => $notification_content['admin_db_message'],
                                    ])
                                    ->send();


        }catch(Exception $e) {}

    }

    //card buy charges function
    public function cardCharges($amount,$charges,$wallet,$card_currency){
        $sPrecision = get_wallet_precision($wallet->currency);
        $exchange_rate = $wallet->currency->rate/$card_currency->rate;

        $data['exchange_rate']         = $exchange_rate;
        $data['card_amount']           = $amount;
        $data['card_currency']         = $card_currency->currency_code;
        $data['card_currency_rate']    = $card_currency->rate;

        $data['from_amount']           = $amount * $exchange_rate;
        $data['from_currency']         = $wallet->currency->code;
        $data['from_currency_rate']    = $wallet->currency->rate;

        $data['percent_charge']        = ($data['from_amount'] / 100) * $charges->percent_charge ?? 0;
        $data['fixed_charge']          = $exchange_rate * $charges->fixed_charge ?? 0;
        $data['total_charge']          = $data['percent_charge'] + $data['fixed_charge'];
        $data['from_wallet_balance']   = $wallet->balance;
        $data['payable']               = $data['from_amount'] + $data['total_charge'];
        $data['card_platform']         = "Stripe Card";
        $data['precision_digit']       = $sPrecision;

        return $data;

    }
}
