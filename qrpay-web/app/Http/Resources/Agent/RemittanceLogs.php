<?php

namespace App\Http\Resources\Agent;

use App\Constants\GlobalConst;
use App\Models\Admin\BasicSettings;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class RemittanceLogs extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $statusInfo = [
            "success" =>      1,
            "pending" =>      2,
            "rejected" =>     3,
        ];
        $basic_settings = BasicSettings::first();
        if( @$this->details->remitance_type == "wallet-to-wallet-transfer"){
            $transactionType = @$basic_settings->site_name." Wallet";

        }else{
            $transactionType = ucwords(str_replace('-', ' ', @$this->details->remitance_type));
        }
        if($this->attribute == payment_gateway_const()::SEND){
            if(@$this->details->remitance_type == Str::slug(GlobalConst::TRX_WALLET_TO_WALLET_TRANSFER)){
                return[
                    'id' => @$this->id,
                    'type' =>$this->attribute,
                    'trx' => @$this->trx_id,
                    'transaction_type' => $this->type,
                    'transaction_heading' => "Send Remitance to @" . $this->details->receiver_recipient->email,
                    'request_amount' => getAmount(@$this->request_amount,get_wallet_precision()).' '.get_default_currency_code() ,
                    'total_charge' => getAmount(@$this->charge->total_charge,get_wallet_precision()).' '.get_default_currency_code(),
                    'exchange_rate' => "1".' '. get_default_currency_code().' = '. get_amount($this->details->to_country->rate,$this->details->to_country->code),
                    'payable' => getAmount(@$this->payable,get_wallet_precision()).' '.get_default_currency_code(),
                    'sending_country' => @$this->details->form_country,
                    'receiving_country' => @$this->details->to_country->country,
                    'sender_recipient_name' => @$this->details->sender_recipient->firstname.' '.@$this->details->sender_recipient->lastname,
                    'receiver_recipient_name' => @$this->details->receiver_recipient->firstname.' '.@$this->details->receiver_recipient->lastname,
                    'remittance_type' => Str::slug(GlobalConst::TRX_WALLET_TO_WALLET_TRANSFER) ,
                    'remittance_type_name' => $transactionType ,
                    'recipient_get' =>  get_amount(@$this->details->recipient_amount,$this->details->to_country->code),
                    'current_balance' => getAmount(@$this->available_balance,get_wallet_precision()).' '.get_default_currency_code(),
                    'status' => @$this->stringStatus->value ,
                    'date_time' => @$this->created_at ,
                    'status_info' =>(object)@$statusInfo ,
                    'rejection_reason' =>$this->reject_reason??"" ,
                ];
            }elseif(@$this->details->remitance_type == Str::slug(GlobalConst::TRX_BANK_TRANSFER)){
                return[
                    'id' => @$this->id,
                    'type' =>$this->attribute,
                    'trx' => @$this->trx_id,
                    'transaction_type' => $this->type,
                    'transaction_heading' => "Send Remitance to @" . $this->details->receiver_recipient->email,
                    'request_amount' => getAmount(@$this->request_amount,get_wallet_precision()).' '.get_default_currency_code() ,
                    'total_charge' => getAmount(@$this->charge->total_charge,get_wallet_precision()).' '.get_default_currency_code(),
                    'exchange_rate' => "1".' '. get_default_currency_code().' = '. get_amount($this->details->to_country->rate,$this->details->to_country->code),
                    'payable' => getAmount(@$this->payable,get_wallet_precision()).' '.get_default_currency_code(),
                    'sending_country' => @$this->details->form_country,
                    'receiving_country' => @$this->details->to_country->country,
                    'sender_recipient_name' => @$this->details->sender_recipient->firstname.' '.@$this->details->sender_recipient->lastname,
                    'receiver_recipient_name' => @$this->details->receiver_recipient->firstname.' '.@$this->details->receiver_recipient->lastname,
                    'remittance_type' => Str::slug(GlobalConst::TRX_BANK_TRANSFER) ,
                    'remittance_type_name' => $transactionType ,
                    'recipient_get' =>  get_amount(@$this->details->recipient_amount,$this->details->to_country->code),
                    'bank_name' => ucwords(str_replace('-', ' ', @$this->details->receiver_recipient->alias)),
                    'current_balance' => getAmount(@$this->available_balance,get_wallet_precision()).' '.get_default_currency_code(),
                    'status' => @$this->stringStatus->value ,
                    'date_time' => @$this->created_at ,
                    'status_info' =>(object)@$statusInfo ,
                    'rejection_reason' =>$this->reject_reason??"",
                ];
            }elseif(@$this->details->remitance_type == Str::slug(GlobalConst::TRX_CASH_PICKUP)){
                return[
                    'id' => @$this->id,
                    'type' =>$this->attribute,
                    'trx' => @$this->trx_id,
                    'transaction_type' => $this->type,
                    'transaction_heading' => "Send Remitance to @" . $this->details->receiver_recipient->email,
                    'request_amount' => getAmount(@$this->request_amount,get_wallet_precision()).' '.get_default_currency_code() ,
                    'total_charge' => getAmount(@$this->charge->total_charge,get_wallet_precision()).' '.get_default_currency_code(),
                    'exchange_rate' => "1".' '. get_default_currency_code().' = '. get_amount($this->details->to_country->rate,$this->details->to_country->code),
                    'payable' => getAmount(@$this->payable,get_wallet_precision()).' '.get_default_currency_code(),
                    'sending_country' => @$this->details->form_country,
                    'receiving_country' => @$this->details->to_country->country,
                    'sender_recipient_name' => @$this->details->sender_recipient->firstname.' '.@$this->details->sender_recipient->lastname,
                    'receiver_recipient_name' => @$this->details->receiver_recipient->firstname.' '.@$this->details->receiver_recipient->lastname,
                    'remittance_type' => Str::slug(GlobalConst::TRX_CASH_PICKUP) ,
                    'remittance_type_name' => $transactionType ,
                    'receipient_get' =>  get_amount(@$this->details->recipient_amount,$this->details->to_country->code),
                    'pickup_point' => ucwords(str_replace('-', ' ', @$this->details->receiver_recipient->alias)),
                    'current_balance' => getAmount(@$this->available_balance,get_wallet_precision()).' '.get_default_currency_code(),
                    'status' => @$this->stringStatus->value ,
                    'date_time' => @$this->created_at ,
                    'status_info' =>(object)@$statusInfo ,
                    'rejection_reason' =>$this->reject_reason??"" ,
                ];
            }
        }
    }
}
