@extends('agent.layouts.master')

@push('css')

@endpush

@section('breadcrumb')
    @include('agent.components.breadcrumb',['breadcrumbs' => [
        [
            'name'  => __("Dashboard"),
            'url'   => setRoute("agent.dashboard"),
        ]
    ], 'active' => __(@$page_title)])
@endsection

@section('content')
<div class="body-wrapper">
    <div class="dashboard-area mt-10">
        <div class="dashboard-header-wrapper">
            <h3 class="title">{{__("Add Money")}}</h3>
        </div>
    </div>
    <div class="row mb-30-none">
        <div class="col-lg-6 mb-30">
            <div class="dash-payment-item-wrapper">
                <div class="dash-payment-item active">
                    <div class="dash-payment-title-area">
                        <span class="dash-payment-badge">!</span>
                        <h5 class="title">{{ __($page_title) }}</h5>
                    </div>
                    <div class="dash-payment-body">
                        <form class="card-form" action="{{ setRoute("agent.add.money.submit") }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-xl-12 col-lg-12 form-group text-center">
                                    <div class="exchange-area">
                                        <code class="d-block text-center"><span>{{ __("Exchange Rate") }}</span> <span class="rate-show">--</span></code>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 form-group">
                                    <label>{{ __("Payment Gateway") }}<span>*</span></label>
                                    <select class="form--control nice-select gateway-select" name="currency">
                                        @forelse ($payment_gateways_currencies ?? [] as $item)
                                            <option
                                                value="{{$item->alias}}"
                                                data-currency="{{ $item->currency_code }}"
                                                data-min_amount="{{ $item->min_limit }}"
                                                data-max_amount="{{ $item->max_limit }}"
                                                data-percent_charge="{{ $item->percent_charge }}"
                                                data-fixed_charge="{{ $item->fixed_charge }}"
                                                data-rate="{{ $item->rate }}"
                                                data-crypto="{{ $item->gateway->crypto }}"
                                                data-daily_limit="{{ $item->daily_limit}}"
                                                data-monthly_limit="{{ $item->monthly_limit}}"
                                                data-currency-id="{{ $item->id}}"
                                                >
                                                {{ $item->name }}
                                            </option>
                                        @empty
                                            <option  disabled >{{ __('No Gateway Available') }}</option>
                                        @endforelse
                                    </select>
                                </div>
                                <div class="col-xl-6 col-lg-6 form-group">

                                    <label>{{ __("Amount") }}<span>*</span></label>
                                    <div class="input-group">
                                        <input type="text" class="form--control" placeholder="{{__('enter Amount')}}" required name="amount" value="{{ old("amount") }}">
                                        <select class="form--control nice-select">
                                            <option value="{{ get_default_currency_code() }}">{{ get_default_currency_code() }}</option>
                                        </select>
                                    </div>

                                </div>
                                <div class="col-xl-12 col-lg-12 form-group">
                                    <div class="note-area">
                                        <code class="d-block fees-show">--</code>
                                        <code class="d-block mt-10 text-end text--base balance-show">{{ __("Available Balance") }} {{ authWalletBalance() }} {{ get_default_currency_code() }}</code>
                                    </div>
                                </div>
                                @if($basic_settings->agent_pin_verification == true)
                                        <div class="col-xl-12 col-lg-12">
                                            <a href="javascript:void(0)" class="btn--base w-100 btn-loading" data-bs-toggle="modal" data-bs-target="#checkPin">{{ __("Add Money") }} <i class="fas fa-plus-circle ms-1"></i></a>

                                        </div>
                                    </div>
                                    @include('agent.components.modal.pin-check')
                                @else
                                    <div class="col-xl-12 col-lg-12">
                                        <button type="submit" class="btn--base w-100 btn-loading">{{ __("Add Money") }} <i class="fas fa-plus-circle ms-1"></i></button>
                                    </div>
                                </div>
                                @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 mb-30">
            <div class="dash-payment-item-wrapper">
                <div class="dash-payment-item active">
                    <div class="dash-payment-title-area">
                        <span class="dash-payment-badge">!</span>
                        <h5 class="title">{{__("Add Money Preview")}}</h5>
                    </div>
                    <div class="dash-payment-body">
                        <div class="preview-list-wrapper">
                            <div class="preview-list-item">
                                <div class="preview-list-left">
                                    <div class="preview-list-user-wrapper">
                                        <div class="preview-list-user-icon">
                                            <i class="las la-receipt"></i>
                                        </div>
                                        <div class="preview-list-user-content">
                                            <span>{{ __("Entered Amount") }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="preview-list-right">
                                    <span class="request-amount">--</span>
                                </div>
                            </div>
                            <div class="preview-list-item">
                                <div class="preview-list-left">
                                    <div class="preview-list-user-wrapper">
                                        <div class="preview-list-user-icon">
                                            <i class="las la-battery-half"></i>
                                        </div>
                                        <div class="preview-list-user-content">
                                            <span>{{ __("Total Fees & Charges") }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="preview-list-right">
                                    <span class="fees">--</span>
                                </div>
                            </div>
                            <div class="preview-list-item">
                                <div class="preview-list-left">
                                    <div class="preview-list-user-wrapper">
                                        <div class="preview-list-user-icon">
                                            <i class="lab la-get-pocket"></i>
                                        </div>
                                        <div class="preview-list-user-content">
                                            <span>{{ __("Will Get") }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="preview-list-right">
                                    <span class="will-get">--</span>
                                </div>
                            </div>
                            <div class="preview-list-item">
                                <div class="preview-list-left">
                                    <div class="preview-list-user-wrapper">
                                        <div class="preview-list-user-icon">
                                            <i class="las la-money-check-alt"></i>
                                        </div>
                                        <div class="preview-list-user-content">
                                            <span class="last">{{ __("Total Payable Amount") }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="preview-list-right">
                                    <span class="text--warning last pay-in-total">--</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
             {{-- limit section  --}}
             <div class="dash-payment-item-wrapper limit">
                <div class="dash-payment-item active">
                    <div class="dash-payment-title-area">
                        <span class="dash-payment-badge">!</span>
                        <h5 class="title">{{__("Limit Information")}}</h5>
                    </div>
                    <div class="dash-payment-body">
                        <div class="preview-list-wrapper">
                            <div class="preview-list-item">
                                <div class="preview-list-left">
                                    <div class="preview-list-user-wrapper">
                                        <div class="preview-list-user-icon">
                                            <i class="las la-wallet"></i>
                                        </div>
                                        <div class="preview-list-user-content">
                                            <span>{{ __("Transaction Limit") }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="preview-list-right">
                                    <span class="limit-show">--</span>
                                </div>
                            </div>

                                <div class="preview-list-item">
                                    <div class="preview-list-left">
                                        <div class="preview-list-user-wrapper">
                                            <div class="preview-list-user-icon">
                                                <i class="las la-wallet"></i>
                                            </div>
                                            <div class="preview-list-user-content">
                                                <span>{{ __("Daily Limit") }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="preview-list-right">
                                        <span class="limit-daily">--</span>
                                    </div>
                                </div>
                                <div class="preview-list-item">
                                    <div class="preview-list-left">
                                        <div class="preview-list-user-wrapper">
                                            <div class="preview-list-user-icon">
                                                <i class="las la-wallet"></i>
                                            </div>
                                            <div class="preview-list-user-content">
                                                <span>{{ __("Remaining Daily Limit") }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="preview-list-right">
                                        <span class="daily-remaining">--</span>
                                    </div>
                                </div>


                                <div class="preview-list-item">
                                    <div class="preview-list-left">
                                        <div class="preview-list-user-wrapper">
                                            <div class="preview-list-user-icon">
                                                <i class="las la-wallet"></i>
                                            </div>
                                            <div class="preview-list-user-content">
                                                <span>{{ __("Monthly Limit") }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="preview-list-right">
                                        <span class="limit-monthly">--</span>
                                    </div>
                                </div>
                                <div class="preview-list-item">
                                    <div class="preview-list-left">
                                        <div class="preview-list-user-wrapper">
                                            <div class="preview-list-user-icon">
                                                <i class="las la-wallet"></i>
                                            </div>
                                            <div class="preview-list-user-content">
                                                <span>{{ __("Remaining Monthly Limit") }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="preview-list-right">
                                        <span class="monthly-remaining">--</span>
                                    </div>
                                </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="dashboard-list-area mt-20">
        <div class="dashboard-header-wrapper">
            <h4 class="title">{{ __("Add Money Logs") }}</h4>
            <div class="dashboard-btn-wrapper">
                <div class="dashboard-btn mb-2">
                    <a href="{{ setRoute('agent.transactions.index','add-money') }}" class="btn--base">{{__("View More")}}</a>
                </div>
            </div>
        </div>
        <div class="dashboard-list-wrapper">
            @include('agent.components.transaction-log',compact("transactions"))
        </div>
    </div>
</div>
@endsection

@push('script')
    <script>
        var defualCurrency      = "{{ get_default_currency_code() }}";
        var defualCurrencyRate  = "{{ get_default_currency_rate() }}";
        var pricison            = "{{  get_precision_from_admin()['fiat_precision_value'] }}";
        var walletCurrencyType  = "{{ get_default_currency()->type }}";
        var walletCurrencyId    = "{{ get_default_currency()->id }}";

        $('select[name=currency]').on('change',function(){
            isCrypto(),
            getExchangeRate();
            getLimit();
            getFees();
            getDailyMonthlyLimit();
            get_remaining_limits();
            activeItems();
        });
        $(document).ready(function(){
            isCrypto(),
            getExchangeRate();
            getLimit();
            getFees();
            getDailyMonthlyLimit();
            get_remaining_limits();
        });
        $("input[name=amount]").keyup(function(){
             getFees();
             activeItems();
        });


        function acceptVar() {
            var selectedVal = $("select[name=currency] :selected");
            var currencyCode = $("select[name=currency] :selected").attr("data-currency");
            var currencyRate = $("select[name=currency] :selected").attr("data-rate");
            var cryptoType  = $("select[name=currency] :selected").attr("data-crypto");
            var currencyMinAmount = $("select[name=currency] :selected").attr("data-min_amount");
            var currencyMaxAmount = $("select[name=currency] :selected").attr("data-max_amount");
            var currencyFixedCharge = $("select[name=currency] :selected").attr("data-fixed_charge");
            var currencyPercentCharge = $("select[name=currency] :selected").attr("data-percent_charge");
            var currencyDailyLimit      = $("select[name=currency] :selected").attr("data-daily_limit");
            var currencyMonthlyLimit    = $("select[name=currency] :selected").attr("data-monthly_limit");

            if(walletCurrencyType == "CRYPTO"){
                var walletPrecison = "{{ get_precision_from_admin()['crypto_precision_value'] }}";
            }else{
                var walletPrecison = "{{  get_precision_from_admin()['fiat_precision_value'] }}";
            }

            return {
                currencyCode:currencyCode,
                currencyRate:currencyRate,
                cryptoType:cryptoType,
                currencyMinAmount:currencyMinAmount,
                currencyMaxAmount:currencyMaxAmount,
                currencyFixedCharge:currencyFixedCharge,
                currencyPercentCharge:currencyPercentCharge,
                currencyDailyLimit:currencyDailyLimit,
                currencyMonthlyLimit:currencyMonthlyLimit,
                selectedVal:selectedVal,
                wPrecison:walletPrecison,

            };
        }
        function isCrypto(){
            var type = acceptVar().cryptoType;
            if(type == 1 ){
                presion= "{{ get_precision_from_admin()['crypto_precision_value'] }}";
            }else{
                presion= "{{  get_precision_from_admin()['fiat_precision_value'] }}";
            }
            return presion;
        }
        function getExchangeRate() {
            var currencyCode = acceptVar().currencyCode;
            var currencyRate = acceptVar().currencyRate;
            var currencyMinAmount = acceptVar().currencyMinAmount;
            var currencyMaxAmount = acceptVar().currencyMaxAmount;
            $('.rate-show').html("1 " + defualCurrency + " = " + parseFloat(currencyRate).toFixed(presion) + " " + currencyCode);
            return currencyRate;
        }
        function getLimit() {
            var sender_currency = acceptVar().currencyCode;
            var sender_currency_rate = acceptVar().currencyRate;
            var min_limit = acceptVar().currencyMinAmount;
            var max_limit =acceptVar().currencyMaxAmount;
            if($.isNumeric(min_limit) || $.isNumeric(max_limit)) {
                var min_limit_calc = parseFloat(min_limit/sender_currency_rate).toFixed(acceptVar().wPrecison);
                var max_limit_clac = parseFloat(max_limit/sender_currency_rate).toFixed(acceptVar().wPrecison);
                $('.limit-show').html( min_limit_calc + " " + defualCurrency + " - " + max_limit_clac + " " + defualCurrency);
                return {
                    minLimit:min_limit_calc,
                    maxLimit:max_limit_clac,
                };
            }else {
                $('.limit-show').html("--");
                return {
                    minLimit:0,
                    maxLimit:0,
                };
            }
        }
        function getDailyMonthlyLimit(){
            var pricison = isCrypto();
            var wCurrencyCode = defualCurrency;
            var wCurrencyRate = acceptVar().defualCurrencyRate;
            var exchange_rate = getExchangeRate();
            var daily_limit = acceptVar().currencyDailyLimit;
            var monthly_limit = acceptVar().currencyMonthlyLimit;

            if($.isNumeric(daily_limit) && $.isNumeric(monthly_limit)) {
                if(daily_limit > 0 ){
                    var daily_limit_calc = parseFloat(daily_limit / exchange_rate).toFixed(acceptVar().wPrecison);
                    $('.limit-daily').html( daily_limit_calc + " " + wCurrencyCode);
                }else{
                    $('.limit-daily').html("{{ __('unlimited') }}");
                }

                if(monthly_limit > 0 ){
                    var montly_limit_clac = parseFloat(monthly_limit / exchange_rate).toFixed(acceptVar().wPrecison);
                    $('.limit-monthly').html( montly_limit_clac + " " + wCurrencyCode);

                }else{
                    $('.limit-monthly').html("{{ __('unlimited') }}");
                }

            }else {
                $('.limit-daily').html("--");
                $('.limit-monthly').html("--");
                return {
                    dailyLimit:0,
                    monthlyLimit:0,
                };
            }

        }

        function feesCalculation() {
            var sender_currency = acceptVar().currencyCode;
            var sender_currency_rate = acceptVar().currencyRate;
            var sender_amount = $("input[name=amount]").val();
            (sender_amount === "" || isNaN(sender_amount)) ? (sender_amount = 0) : (sender_amount = sender_amount);

            var fixed_charge = acceptVar().currencyFixedCharge;
            var percent_charge = acceptVar().currencyPercentCharge;
            if ($.isNumeric(percent_charge) && $.isNumeric(fixed_charge) && $.isNumeric(sender_amount)) {
                // Process Calculation
                var fixed_charge_calc = parseFloat(fixed_charge);
                var percent_charge_calc = parseFloat(sender_currency_rate)*(parseFloat(sender_amount) / 100) * parseFloat(percent_charge);
                var total_charge = parseFloat(fixed_charge_calc) + parseFloat(percent_charge_calc);
                total_charge = parseFloat(total_charge).toFixed(presion);
                // return total_charge;
                return {
                    total: total_charge,
                    fixed: fixed_charge_calc,
                    percent: percent_charge,
                };
            } else {
                // return "--";
                return false;
            }
        }
        function getFees() {
            var sender_currency = acceptVar().currencyCode;
            var percent = acceptVar().currencyPercentCharge;
            var charges = feesCalculation();
            if (charges == false) {
                return false;
            }
            $(".fees-show").html("{{ __('charge') }}: " + parseFloat(charges.fixed).toFixed(presion) + " " + sender_currency + " + " + parseFloat(charges.percent).toFixed(presion) + "% = " + parseFloat(charges.total).toFixed(presion) + " " + sender_currency);
        }
        function activeItems(){
            var selectedVal = acceptVar().selectedVal.val();
            if(selectedVal === undefined || selectedVal === '' || selectedVal === null){
                return false;
            }else{
                return getPreview();
            }
        }
        function getPreview() {
            var senderAmount = $("input[name=amount]").val();
            var sender_currency = acceptVar().currencyCode;
            var sender_currency_rate = acceptVar().currencyRate;
            (senderAmount === "" || isNaN(senderAmount)) ? senderAmount = 0 : senderAmount = senderAmount;

            // Sending Amount
            $('.request-amount').text(parseFloat(senderAmount).toFixed(acceptVar().wPrecison) + " " + defualCurrency);

            // Fees
            var charges = feesCalculation();
            // console.log(total_charge + "--");
            $('.fees').text(charges.total + " " + sender_currency);

            // will get amount

            var willGet = parseFloat(senderAmount).toFixed(acceptVar().wPrecison);
            $('.will-get').text(willGet + " " + defualCurrency);

            // Pay In Total
            var totalPay = parseFloat(senderAmount) * parseFloat(sender_currency_rate)
                var pay_in_total = parseFloat(charges.total) + parseFloat(totalPay);
            $('.pay-in-total').text(parseFloat(pay_in_total).toFixed(presion) + " " + sender_currency);

        }
        function get_remaining_limits(){
        var csrfToken           = $('meta[name="csrf-token"]').attr('content');
        var user_field          = "agent_id";
        var user_id             = "{{ userGuard()['user']->id }}";
        var transaction_type    = "{{ payment_gateway_const()::TYPEADDMONEY }}";
        var currency_id         = walletCurrencyId;
        var sender_amount       = $("input[name=amount]").val();

        (sender_amount == "" || isNaN(sender_amount)) ? sender_amount = 0 : sender_amount = sender_amount;

        var charge_id           = acceptVar().selectedVal.data('currency-id');
        var attribute           = "{{ payment_gateway_const()::SEND }}";

        $.ajax({
            type: 'POST',
            url: "{{ route('global.get.total.transactions') }}",
            data: {
                _token:             csrfToken,
                user_field:         user_field,
                user_id:            user_id,
                transaction_type:   transaction_type,
                currency_id:        currency_id,
                sender_amount:      sender_amount,
                charge_id:          charge_id,
                attribute:          attribute,
            },
            success: function(response) {
                var sender_currency = defualCurrency;
                var daily_limit = acceptVar().currencyDailyLimit;
                var monthly_limit = acceptVar().currencyMonthlyLimit


                var status  = response.status;
                var message = response.message;
                var amount_data = response.data;

                if(status == false){
                    $('.addMoneyBtn').attr('disabled',true);
                    if(daily_limit > 0){
                        $('.daily-remaining').html(amount_data.remainingDailyTxnSelected + " " + sender_currency);
                        throwMessage('error',[message]);
                        return false;
                    }else{
                        $('.daily-remaining').html("{{ __('unlimited') }}");
                    }
                    if(monthly_limit > 0){
                        $('.monthly-remaining').html(amount_data.remainingMonthlyTxnSelected + " " + sender_currency);
                        throwMessage('error',[message]);
                        return false;
                    }else{
                        $('.monthly-remaining').html("{{ __('unlimited') }}");
                    }

                }else{
                    $('.addMoneyBtn').attr('disabled',false);
                    if(daily_limit > 0){
                        $('.daily-remaining').html(amount_data.remainingDailyTxnSelected + " " + sender_currency);
                    }else{
                        $('.daily-remaining').html("{{ __('unlimited') }}");
                    }
                    if(monthly_limit > 0){
                        $('.monthly-remaining').html(amount_data.remainingMonthlyTxnSelected + " " + sender_currency);
                    }else{
                        $('.monthly-remaining').html("{{ __('unlimited') }}");
                    }
                }
            },
        });
    }


    </script>
@endpush
