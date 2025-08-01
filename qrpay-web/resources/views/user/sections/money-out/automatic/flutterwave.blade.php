@extends('user.layouts.master')
@php
    $iso2 = $country->iso2;
    $branch_status = branch_required_permission($iso2);
    $pricison = $gateway->crypto == 1 ? 8 : 2;
@endphp

@push('css')

@endpush

@section('breadcrumb')
    @include('user.components.breadcrumb',['breadcrumbs' => [
        [
            'name'  => __("Dashboard"),
            'url'   => setRoute("user.dashboard"),
        ]
    ], 'active' => __("withdraw")])
@endsection

@section('content')
<div class="body-wrapper">
    <div class="dashboard-area mt-10">
        <div class="dashboard-header-wrapper">
            <h3 class="title">{{__(@$page_title)}}</h3>
        </div>
    </div>
    <div class="row mb-30-none justify-content-center">
        <div class="col-lg-6 mb-30">
            <div class="dash-payment-item-wrapper">
                <div class="dash-payment-item active">
                    <div class="dash-payment-title-area">
                        <span class="dash-payment-badge">!</span>
                    </div>
                    <div class="dash-payment-body">
                        <form class="card-form" action="{{ setRoute("user.money.out.confirm.automatic") }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="gateway_name" value="{{ strtolower($gateway->name) }}">

                            <div class="row">
                                {{-- call components --}}
                                @if($country->currency_code == "USD")
                                    @include('user.sections.money-out.automatic.flutterwave-country-component.usd-accounts')
                                @elseif ($country->currency_code == "EUR" || $country->currency_code == "GBP" )
                                    @include('user.sections.money-out.automatic.flutterwave-country-component.eur-gbp-accounts')
                                @elseif ($country->currency_code == "NGN")
                                    @include('user.sections.money-out.automatic.flutterwave-country-component.ngn-accounts')
                                @elseif ($country->currency_code == "ZAR")
                                    @include('user.sections.money-out.automatic.flutterwave-country-component.zar-accounts')
                                @elseif ($country->currency_code == "TZS")
                                    @include('user.sections.money-out.automatic.flutterwave-country-component.tzs-accounts')
                                @elseif ($country->currency_code == "KES")
                                    @include('user.sections.money-out.automatic.flutterwave-country-component.kes-accounts')
                                @else
                                    @include('user.sections.money-out.automatic.flutterwave-country-component.african-accounts')
                                @endif
                                <div class="col-xl-12 col-lg-12">
                                    <button type="submit" class="btn--base w-100 btn-loading withdraw" > {{ __("confirm") }}</button>
                                </div>
                            </div>
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
                        <h5 class="title">{{__("Withdraw Money Information!")}}</h5>
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
                                    <span class="request-amount">{{ number_format(@$moneyOutData->amount,$pricison )}} {{ get_default_currency_code() }}</span>
                                </div>
                            </div>
                            <div class="preview-list-item">
                                <div class="preview-list-left">
                                    <div class="preview-list-user-wrapper">
                                        <div class="preview-list-user-icon">
                                            <i class="las la-exchange-alt"></i>
                                        </div>
                                        <div class="preview-list-user-content">
                                            <span>{{ __("Exchange Rate") }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="preview-list-right">
                                    <span class="request-amount">{{ __("1") }} {{ get_default_currency_code() }} =  {{ number_format(@$moneyOutData->gateway_rate,$pricison )}} {{ @$moneyOutData->gateway_currency }}</span>
                                </div>
                            </div>
                            <div class="preview-list-item">
                                <div class="preview-list-left">
                                    <div class="preview-list-user-wrapper">
                                        <div class="preview-list-user-icon">
                                            <i class="lab la-get-pocket"></i>
                                        </div>
                                        <div class="preview-list-user-content">
                                            <span>{{ __("Conversion Amount") }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="preview-list-right">
                                    <span class="conversion">{{ number_format(@$moneyOutData->conversion_amount,$pricison )}} {{ @$moneyOutData->gateway_currency }}</span>
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
                                    <span class="fees">{{ number_format(@$moneyOutData->gateway_charge,$pricison )}} {{ @$moneyOutData->gateway_currency }}</span>
                                </div>
                            </div>

                            <div class="preview-list-item">
                                <div class="preview-list-left">
                                    <div class="preview-list-user-wrapper">
                                        <div class="preview-list-user-icon">
                                            <i class="las la-money-check-alt"></i>
                                        </div>
                                        <div class="preview-list-user-content">
                                            <span class="">{{ __("Will Get") }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="preview-list-right">
                                    <span class="text--success ">{{ number_format(@$moneyOutData->will_get,$pricison )}} {{ @$moneyOutData->gateway_currency }}</span>
                                </div>
                            </div>
                            <div class="preview-list-item">
                                <div class="preview-list-left">
                                    <div class="preview-list-user-wrapper">
                                        <div class="preview-list-user-icon">
                                            <i class="las la-money-check-alt"></i>
                                        </div>
                                        <div class="preview-list-user-content">
                                            <span class="last">{{ __("Total Payable") }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="preview-list-right">
                                    <span class="text--warning last">{{ number_format(@$moneyOutData->payable,$pricison )}} {{ get_default_currency_code() }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('script')
<script>
    var branch_status = "{{ $branch_status }}"
    if(branch_status == true){
        $("select[name=bank_name]").change(function(){
            getBranches();

        });
        function acceptVar() {
                var selected_bank = $("select[name=bank_name] :selected");
                var selected_bank_branch = $("select[name=branch_code] :selected");
                var account_number = $("input[name=account_number]").val();
                var beneficiary_name = $("input[name=beneficiary_name]").val();
            return {
                selected_bank:selected_bank,
                selected_bank_branch:selected_bank_branch,
                account_number:account_number,
                beneficiary_name:beneficiary_name,
            };
        }
        function getBranches() {
            var url = "{{ route('user.money.out.get.flutterwave.bank.branches') }}";
            var bank_id = acceptVar().selected_bank.data('bank-id');
            var iso2 = "{{ $iso2 }}";
            var token = '{{ csrf_token() }}';

            var data = {_token: token, bank_id: bank_id, iso2: iso2};

            $.post(url, data, function(response) {
                var result = response;
                if (result.status == true) {
                    var branches = result.branches; // branches array list

                    // Clear the previous options and append the default option
                    var branchOptions = `
                        <option disabled selected value="">{{ __("Select Bank Branch") }}</option>
                    `;

                    // Loop through each branch and create an option element
                    branches.forEach(function(branch) {
                        branchOptions += `
                            <option value="${branch.branch_code}">${branch.branch_name}</option>
                        `;
                    });

                    // Append the HTML code to the .branches-list div for branches
                    $('.branches-list').html(`
                        <div class="col-lg-12 form-group">
                            <label for="branch_code">{{ __("Bank Branch") }} <span class="text-danger">*</span></label>
                            <select name="branch_code" class="form--control select2-basic" required>
                                ${branchOptions}
                            </select>
                        </div>
                    `);

                    // Reinitialize select2 for the dynamically created select element
                    $('.select2-basic').select2();
                }else{
                    $('.branches-list').html(`
                        <div class="col-lg-12 form-group">
                            <label for="branch_code">{{ __("Bank Branch") }} <span class="text-danger">*</span></label>
                            <select name="branch_code" class="form--control select2-basic" required>
                                <option disabled selected value="">{{ __("No branches found for specified bank") }}</option>
                            </select>
                        </div>
                    `);
                    $('.select2-basic').select2();
                }
            });

        }
        $('form').on('submit', function(e) {
            var bank = acceptVar().selected_bank.val();
            var bank_branch = acceptVar().selected_bank_branch.val();
            var account_number = acceptVar().account_number;
            var beneficiary_name = acceptVar().beneficiary_name;

            if (bank_branch == '' || bank == '' || account_number == '' || beneficiary_name =='') {
                e.preventDefault();
                throwMessage('error',['{{ __("Please select all requird fields.") }}']);
                $('.select2-basic').focus();
                location.reload();
            }
        });
    }
</script>
@endpush
