<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;

class AgentMoneyOutLogs extends JsonResource
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
            if($this->attribute == payment_gateway_const()::SEND){
                return[
                    'id' => @$this->id,
                    'type' =>$this->attribute,
                    'trx' => @$this->trx_id,
                    'transaction_type' => $this->type,
                    'transaction_heading' => "Money Out to @" . @$this->details->receiver_email,
                    'request_amount' => getAmount(@$this->request_amount,get_wallet_precision()).' '.get_default_currency_code() ,
                    'total_charge' => getAmount(@$this->charge->total_charge,get_wallet_precision()).' '.get_default_currency_code(),
                    'payable' => getAmount(@$this->payable,get_wallet_precision()).' '.get_default_currency_code(),
                    'recipient_received' => getAmount(@$this->details->recipient_amount,get_wallet_precision()).' '.get_default_currency_code(),
                    'current_balance' => getAmount(@$this->available_balance,get_wallet_precision()).' '.get_default_currency_code(),
                    'status' => @$this->stringStatus->value ,
                    'date_time' => @$this->created_at ,
                    'status_info' =>(object)@$statusInfo ,
                ];
            }elseif($this->attribute == payment_gateway_const()::RECEIVED){
                return[
                    'id' => @$this->id,
                    'type' =>$this->attribute,
                    'trx' => @$this->trx_id,
                    'transaction_type' => $this->type,
                    'transaction_heading' => "Money Out to @" . @$this->details->receiver_email,
                    'request_amount' => getAmount(@$this->request_amount,get_wallet_precision()).' '.get_default_currency_code() ,
                    'total_charge' => getAmount(0,get_wallet_precision()).' '.get_default_currency_code(),
                    'payable' => getAmount(@$this->request_amount,get_wallet_precision()).' '.get_default_currency_code(),
                    'recipient_received' => getAmount(@$this->details->recipient_amount,get_wallet_precision()).' '.get_default_currency_code(),
                    'current_balance' => getAmount(@$this->available_balance,get_wallet_precision()).' '.get_default_currency_code(),
                    'status' => @$this->stringStatus->value ,
                    'date_time' => @$this->created_at ,
                    'status_info' =>(object)@$statusInfo ,
                ];

            }
    }
}
