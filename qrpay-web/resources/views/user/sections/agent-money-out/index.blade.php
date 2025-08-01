@extends('user.layouts.master')

@push('css')

@endpush

@section('breadcrumb')
    @include('user.components.breadcrumb',['breadcrumbs' => [
        [
            'name'  => __("Dashboard"),
            'url'   => setRoute("user.dashboard"),
        ]
    ], 'active' => __(@$page_title)])
@endsection

@section('content')
<div class="body-wrapper">
    <div class="dashboard-area mt-10">
        <div class="dashboard-header-wrapper">
            <h3 class="title">{{__(@$page_title)}}</h3>
        </div>
    </div>
    <div class="row mb-30-none">
        <div class="col-xl-6 mb-30">
            <div class="dash-payment-item-wrapper">
                <div class="dash-payment-item active">
                    <div class="dash-payment-title-area">
                        <span class="dash-payment-badge">!</span>
                        <h5 class="title">{{ __(@$page_title) }}</h5>
                    </div>
                    <div class="dash-payment-body">
                        <form class="card-form" action="{{ setRoute('user.agent.money.out.confirmed') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-xl-12 col-lg-12 form-group text-center">
                                    <div class="exchange-area">
                                        <code class="d-block text-center"><span class="fees-show">--</span></code>
                                    </div>
                                </div>
                                <div class="col-xxl-6 col-xl-12 col-lg-6 form-group paste-wrapper">
                                    <label>{{ __("email Address") }} ({{ __("Agent") }})<span class="text--base">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text copytext"><span>{{ __("Email") }}</span></span>
                                        </div>
                                        <input type="email" name="email" class="form--control checkUser" id="username" placeholder="{{ __('enter Email Address') }}" value="{{ old('email') }}" />
                                    </div>
                                    <button type="button" class="paste-badge scan"  data-toggle="tooltip" title="Scan QR"><i class="fas fa-camera"></i></button>
                                    <label class="exist text-start"></label>

                                </div>

                                <div class="col-xxl-6 col-xl-12 col-lg-6 form-group">
                                    <label>{{ __("Amount") }}<span>*</span></label>
                                    <div class="input-group">
                                        <input type="text" class="form--control number-input" required placeholder="{{ __("enter Amount") }}" name="amount" value="{{ old("amount") }}">
                                        <select class="form--control nice-select currency" name="currency">
                                            <option value="{{ get_default_currency_code() }}">{{ get_default_currency_code() }}</option>
                                        </select>
                                    </div>
                                    <code class="d-block mt-10 text-end text--warning balance-show">{{ __("Available Balance") }} {{ authWalletBalance() }} {{ get_default_currency_code() }}</code>
                                </div>

                                @if($basic_settings->user_pin_verification == true)
                                        <div class="col-xl-12 col-lg-12">
                                            <button type="button" class="btn--base w-100 btn-loading transfer" data-bs-toggle="modal" data-bs-target="#checkPin">{{ __("Confirm Send") }} <i class="fas fa-paper-plane ms-1"></i></button>
                                        </div>
                                    </div>
                                    @include('user.components.modal.pin-check')
                                @else
                                    <div class="col-xl-12 col-lg-12">
                                        <button type="submit" class="btn--base w-100 btn-loading transfer">{{ __("Confirm Send") }} <i class="fas fa-paper-plane ms-1"></i></i></button>
                                    </div>
                                </div>
                                @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6 mb-30">
            <div class="dash-payment-item-wrapper">
                <div class="dash-payment-item active">
                    <div class="dash-payment-title-area">
                        <span class="dash-payment-badge">!</span>
                        <h5 class="title">{{ $page_title }} {{__("Preview")}}</h5>
                    </div>
                    <div class="dash-payment-body">
                        <div class="preview-list-wrapper">

                            <div class="preview-list-item">
                                <div class="preview-list-left">
                                    <div class="preview-list-user-wrapper">
                                        <div class="preview-list-user-icon">
                                            <i class="las la-coins"></i>
                                        </div>
                                        <div class="preview-list-user-content">
                                            <span>{{ __("Entered Amount") }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="preview-list-right">
                                    <span class="fw-bold request-amount">--</span>
                                </div>
                            </div>
                            <div class="preview-list-item">
                                <div class="preview-list-left">
                                    <div class="preview-list-user-wrapper">
                                        <div class="preview-list-user-icon">
                                            <i class="las la-battery-half"></i>
                                        </div>
                                        <div class="preview-list-user-content">
                                            <span>{{ __("Money Out Fee") }}</span>
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
                                            <i class="las la-receipt"></i>
                                        </div>
                                        <div class="preview-list-user-content">
                                            <span>{{ __("Recipient Received") }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="preview-list-right">
                                    <span class="recipient-get">--</span>
                                </div>
                            </div>
                            <div class="preview-list-item">
                                <div class="preview-list-left">
                                    <div class="preview-list-user-wrapper">
                                        <div class="preview-list-user-icon">
                                            <i class="las la-money-check-alt"></i>
                                        </div>
                                        <div class="preview-list-user-content">
                                            <span>{{__("Total Payable")}}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="preview-list-right">
                                    <span class="last payable-total text-warning">--</span>
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
                            @if ($moneyOutCharge->daily_limit > 0)
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
                            @endif
                            @if ($moneyOutCharge->monthly_limit > 0)
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
                            @endif

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="dashboard-list-area mt-20">
        <div class="dashboard-header-wrapper">
            <h4 class="title ">{{__("Money Out Logs")}}</h4>
            <div class="dashboard-btn-wrapper">
                <div class="dashboard-btn mb-2">
                    <a href="{{ setRoute('user.transactions.index','money-out') }}" class="btn--base">{{__("View More")}}</a>
                </div>
            </div>
        </div>
        <div class="dashboard-list-wrapper">
            @include('user.components.transaction-log',compact("transactions"))
        </div>
    </div>
</div>
<div class="modal fade" id="scanModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
            <div class="modal-body text-center">
                <video id="preview" class="p-1 border" style="width:300px;"></video>
            </div>
            <div class="modal-footer justify-content-center">
              <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">@lang('close')</button>
            </div>
      </div>
    </div>
</div>
@endsection

@push('script')
<script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
<script>
    //  'use strict'
    (function ($) {
        $('.scan').click(function(){
            var scanner = new Instascan.Scanner({ video: document.getElementById('preview'), scanPeriod: 5, mirror: false });
            scanner.addListener('scan',function(content){
                var route = '{{url('user/agent/qr/scan/')}}'+'/'+content
                $.get(route, function( data ) {
                    if(data.error){
                        // alert(data.error)
                        throwMessage('error',[data.error]);
                    } else {
                        $("#username").val(data);
                        $("#username").focus()
                    }
                    $('#scanModal').modal('hide')
                });
            });

            Instascan.Camera.getCameras().then(function (cameras){
                if(cameras.length>0){
                    $('#scanModal').modal('show')
                        scanner.start(cameras[0]);
                } else{
                //    alert('No cameras found.');
                    throwMessage('error',["No camera found "]);
                }
            }).catch(function(e){
                // alert('No cameras found.');
                throwMessage('error',["No camera found "]);
            });
        });
        $('.checkUser').on('keyup',function(e){
            var url = '{{ route('user.agent.money.out.check') }}';
            var value = $(this).val();
            var token = '{{ csrf_token() }}';
            if ($(this).attr('name') == 'email') {
                var data = {email:value,_token:token}

            }
            $.post(url,data,function(response) {
                if(response.own){
                    if($('.exist').hasClass('text--success')){
                        $('.exist').removeClass('text--success');
                    }
                    $('.exist').addClass('text--danger').text(response.own);
                    $('.transfer').attr('disabled',true)
                    return false
                }
                if(response['data'] != null){
                    if($('.exist').hasClass('text--danger')){
                        $('.exist').removeClass('text--danger');
                    }
                    $('.exist').text(`Valid agent for transaction.`).addClass('text--success');
                    $('.transfer').attr('disabled',false)
                } else {
                    if($('.exist').hasClass('text--success')){
                        $('.exist').removeClass('text--success');
                    }
                    $('.exist').text('Agent doesn\'t  exists.').addClass('text--danger');
                    $('.transfer').attr('disabled',true)
                    return false
                }

            });
        });
    })(jQuery);
</script>
<script>
     var defualCurrency = "{{ get_default_currency_code() }}";
     var defualCurrencyRate = "{{ get_default_currency_rate() }}";
     var walletCurrencyType = "{{ get_default_currency()->type }}";
     var walletCurrencyId = "{{ get_default_currency()->id }}";

        $(document).ready(function(){
            getLimit();
            getFees();
            getDailyMonthlyLimit();
            get_remaining_limits();
            getPreview();
        });
        $("input[name=amount]").keyup(function(){
             getFees();
             getPreview();
        });

        function acceptVar() {
            var selectedVal             = $("select[name=currency] :selected");
            var currencyCode            = $("select[name=currency] :selected").val();
            var currencyRate            = defualCurrencyRate;
            var currencyMinAmount       ="{{getAmount($moneyOutCharge->min_limit)}}"
            var currencyMaxAmount       = "{{getAmount($moneyOutCharge->max_limit)}}"
            var currencyFixedCharge     = "{{getAmount($moneyOutCharge->fixed_charge)}}"
            var currencyPercentCharge   = "{{getAmount($moneyOutCharge->percent_charge)}}";
            var currencyDailyLimit      = "{{getAmount($moneyOutCharge->daily_limit)}}";
            var currencyMonthlyLimit    = "{{getAmount($moneyOutCharge->monthly_limit)}}";

            if(walletCurrencyType == "CRYPTO"){
                var senderPrecison = "{{ get_precision_from_admin()['crypto_precision_value'] }}";
            }else{
                var senderPrecison = "{{  get_precision_from_admin()['fiat_precision_value'] }}";
            }

            return {
                currencyCode:currencyCode,
                currencyRate:currencyRate,
                currencyMinAmount:currencyMinAmount,
                currencyMaxAmount:currencyMaxAmount,
                currencyFixedCharge:currencyFixedCharge,
                currencyPercentCharge:currencyPercentCharge,
                selectedVal:selectedVal,

                currencyDailyLimit:currencyDailyLimit,
                currencyMonthlyLimit:currencyMonthlyLimit,
                sPrecison:senderPrecison,

            };
        }
        function getLimit() {
            var currencyCode = acceptVar().currencyCode;
            var currencyRate = acceptVar().currencyRate;

            var min_limit = acceptVar().currencyMinAmount;
            var max_limit =acceptVar().currencyMaxAmount;
            if($.isNumeric(min_limit) || $.isNumeric(max_limit)) {
                var min_limit_calc = parseFloat(min_limit/currencyRate).toFixed(acceptVar().sPrecison);
                var max_limit_clac = parseFloat(max_limit/currencyRate).toFixed(acceptVar().sPrecison);
                $('.limit-show').html( min_limit_calc + " " + currencyCode + " - " + max_limit_clac + " " + currencyCode);

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
            var sender_currency = acceptVar().currencyCode;
            var sender_currency_rate = acceptVar().currencyRate;
            var daily_limit = acceptVar().currencyDailyLimit;
            var monthly_limit = acceptVar().currencyMonthlyLimit

            if($.isNumeric(daily_limit) && $.isNumeric(monthly_limit)) {
                if(daily_limit > 0 ){
                    var daily_limit_calc = parseFloat(daily_limit * sender_currency_rate).toFixed(acceptVar().sPrecison);
                    $('.limit-daily').html( daily_limit_calc + " " + sender_currency);
                }else{
                    $('.limit-daily').html("");
                }

                if(monthly_limit > 0 ){
                    var montly_limit_clac = parseFloat(monthly_limit * sender_currency_rate).toFixed(acceptVar().sPrecison);
                    $('.limit-monthly').html( montly_limit_clac + " " + sender_currency);

                }else{
                    $('.limit-monthly').html("");
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
            var currencyCode = acceptVar().currencyCode;
            var currencyRate = acceptVar().currencyRate;
            var sender_amount = $("input[name=amount]").val();
            sender_amount == "" ? (sender_amount = 0) : (sender_amount = sender_amount);

            var fixed_charge = acceptVar().currencyFixedCharge;
            var percent_charge = acceptVar().currencyPercentCharge;
            if ($.isNumeric(percent_charge) && $.isNumeric(fixed_charge) && $.isNumeric(sender_amount)) {
                // Process Calculation
                var fixed_charge_calc = parseFloat(currencyRate * fixed_charge);
                var percent_charge_calc = parseFloat(currencyRate)*(parseFloat(sender_amount) / 100) * parseFloat(percent_charge);
                var total_charge = parseFloat(fixed_charge_calc) + parseFloat(percent_charge_calc);
                total_charge = parseFloat(total_charge).toFixed(acceptVar().sPrecison);
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
            var currencyCode = acceptVar().currencyCode;
            var percent = acceptVar().currencyPercentCharge;
            var charges = feesCalculation();
            if (charges == false) {
                return false;
            }
            $(".fees-show").html("{{ __('Money Out Fee') }}: " + parseFloat(charges.fixed).toFixed(acceptVar().sPrecison) + " " + currencyCode + " + " + parseFloat(charges.percent).toFixed(acceptVar().sPrecison) + "%  ");
        }
        function getPreview() {
                var senderAmount = $("input[name=amount]").val();
                var sender_currency = acceptVar().currencyCode;
                var sender_currency_rate = acceptVar().currencyRate;
                senderAmount == "" ? senderAmount = 0 : senderAmount = senderAmount;
                // Sending Amount
                $('.request-amount').text(parseFloat(senderAmount).toFixed(acceptVar().sPrecison) + " " + defualCurrency);

                // Fees
                var charges = feesCalculation();
                var total_charge = 0;
                if(senderAmount == 0){
                    total_charge = 0;
                }else{
                    total_charge = charges.total;
                }

                $('.fees').text(parseFloat(total_charge).toFixed(acceptVar().sPrecison) + " " + sender_currency);
                // // recipient received
                var recipient = parseFloat(senderAmount) * parseFloat(sender_currency_rate)
                var recipient_get = 0;
                if(senderAmount == 0){
                     recipient_get = 0;
                }else{
                     recipient_get =  parseFloat(recipient);
                }
                $('.recipient-get').text(parseFloat(recipient_get).toFixed(acceptVar().sPrecison) + " " + sender_currency);

                 // Pay In Total
                var totalPay = parseFloat(senderAmount) * parseFloat(sender_currency_rate)
                var pay_in_total = 0;
                if(senderAmount == 0){
                     pay_in_total = 0;
                }else{
                     pay_in_total =  parseFloat(totalPay) + parseFloat(charges.total);
                }
                $('.payable-total').text(parseFloat(pay_in_total).toFixed(acceptVar().sPrecison) + " " + sender_currency);

        }
        function get_remaining_limits(){
            var csrfToken           = $('meta[name="csrf-token"]').attr('content');
            var user_field          = "user_id";
            var user_id             = "{{ userGuard()['user']->id }}";
            var transaction_type    = "{{ payment_gateway_const()::AGENTMONEYOUT }}";
            var currency_id         = walletCurrencyId;
            var sender_amount       = $("input[name=amount]").val();

            (sender_amount == "" || isNaN(sender_amount)) ? sender_amount = 0 : sender_amount = sender_amount;

            var charge_id           = "{{ $moneyOutCharge->id }}";
            var attribute           = "{{ payment_gateway_const()::SEND }}"

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
                    var sender_currency = acceptVar().currencyCode;

                    var status  = response.status;
                    var message = response.message;
                    var amount_data = response.data;

                    if(status == false){
                        $('.transfer').attr('disabled',true);
                        $('.daily-remaining').html(amount_data.remainingDailyTxnSelected + " " + sender_currency);
                        $('.monthly-remaining').html(amount_data.remainingMonthlyTxnSelected + " " + sender_currency);
                        throwMessage('error',[message]);
                        return false;
                    }else{
                        $('.transfer').attr('disabled',false);
                        $('.daily-remaining').html(amount_data.remainingDailyTxnSelected + " " + sender_currency);
                        $('.monthly-remaining').html(amount_data.remainingMonthlyTxnSelected + " " + sender_currency);
                    }
                },
            });
        }

</script>

@endpush
