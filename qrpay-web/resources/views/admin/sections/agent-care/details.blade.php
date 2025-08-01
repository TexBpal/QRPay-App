@extends('admin.layouts.master')

@push('css')
@endpush

@section('page-title')
    @include('admin.components.page-title', ['title' => __($page_title)])
@endsection

@section('breadcrumb')
    @include('admin.components.breadcrumb', [
        'breadcrumbs' => [
            [
                'name' => __('Dashboard'),
                'url' => setRoute('admin.dashboard'),
            ],
        ],
        'active' => __('Agent Care'),
    ])
@endsection

@section('content')
<div class="dashboard-area">
    <div class="dashboard-item-area">
        <div class="row">
            <div class="col-xxxl-4 col-xxl-4 col-xl-4 col-lg-6 col-md-6 col-sm-12 mb-15">
                <div class="dashbord-item">
                    <div class="dashboard-content">
                        <div class="left">
                            <h6 class="title">{{ __("Current Balance") }}</h6>
                            <div class="user-info">
                                <h2 class="user-count">{{ get_amount($data['balance'], get_default_currency_code()) }}</h2>
                            </div>
                        </div>
                        <div class="right">
                            <div class="dashboard-icon">
                                <i class="las la-wallet"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxxl-4 col-xxl-4 col-xl-4 col-lg-6 col-md-6 col-sm-12 mb-15">
                <div class="dashbord-item">
                    <div class="dashboard-content">
                        <div class="left">
                            <h6 class="title">{{ __("Total Add Money") }}</h6>
                            <div class="user-info">
                                <h2 class="user-count">{{ get_amount($data['add_money_amount'], get_default_currency_code()) }}</h2>
                            </div>
                        </div>
                        <div class="right">
                            <div class="dashboard-icon">
                                <i class="las la-sync-alt"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xxxl-4 col-xxl-4 col-xl-4 col-lg-6 col-md-6 col-sm-12 mb-15">
                <div class="dashbord-item">
                    <div class="dashboard-content">
                        <div class="left">
                            <h6 class="title">{{ __("Total Money Out") }}</h6>
                            <div class="user-info">
                                <h2 class="user-count">{{ get_amount($data['money_out_amount'], get_default_currency_code()) }}</h2>
                            </div>
                        </div>
                        <div class="right">
                            <div class="dashboard-icon">
                                <i class="las la-plus-circle"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxxl-4 col-xxl-4 col-xl-4 col-lg-6 col-md-6 col-sm-12 mb-15">
                <div class="dashbord-item">
                    <div class="dashboard-content">
                        <div class="left">
                            <h6 class="title">{{ __("Total Transactions") }}</h6>
                            <div class="user-info">
                                <h2 class="user-count">{{ get_amount($data['total_transaction'], get_default_currency_code()) }}</h2>
                            </div>
                        </div>
                        <div class="right">
                            <div class="dashboard-icon">
                                <i class="las la-sync-alt"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    <div class="custom-card mt-15">
        <div class="card-header">
            <h6 class="title">{{ __("Agent Overview") }}</h6>
        </div>
        <div class="card-body">
            <form class="card-form">
                <div class="row align-items-center mb-10-none">
                    <div class="col-xl-4 col-lg-4 form-group">
                        <div class="user-action-btn-area">
                            <div class="user-action-btn">
                                @include('admin.components.button.custom',[
                                    'type'          => "button",
                                    'class'         => "wallet-balance-update-btn bg--danger one",
                                    'text'          => __("Add/Subtract Balance"),
                                    'icon'          => "las la-wallet me-1",
                                    'permission'    => "admin.agents.wallet.balance.update",
                                ])
                            </div>
                            <div class="user-action-btn">
                                @include('admin.components.link.custom',[
                                    'href'          => setRoute('admin.agents.login.logs',$user->username),
                                    'class'         => "bg--base two",
                                    'icon'          => "las la-sign-in-alt me-1",
                                    'text'          => __("Login Logs"),
                                    'permission'    => "admin.agents.login.logs",
                                ])
                            </div>
                            <div class="user-action-btn">
                                @include('admin.components.link.custom',[
                                    'href'          => "#email-send",
                                    'class'         => "bg--base three modal-btn",
                                    'icon'          => "las la-mail-bulk me-1",
                                    'text'          => __("Send Email"),
                                    'permission'    => "admin.agents.send.mail",
                                ])
                            </div>
                            <div class="user-action-btn">
                                @include('admin.components.link.custom',[
                                    'class'         => "bg--base four login-as-member",
                                    'icon'          => "las la-user-check me-1",
                                    'text'          => __("Login as Member"),
                                    'permission'    => "admin.agents.login.as.member",
                                ])
                            </div>
                            <div class="user-action-btn">
                                @include('admin.components.link.custom',[
                                    'href'          => setRoute('admin.agents.mail.logs',$user->username),
                                    'class'         => "bg--base five",
                                    'icon'          => "las la-history me-1",
                                    'text'          => __("Email Logs"),
                                    'permission'    => "admin.agents.mail.logs",
                                ])
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 form-group">
                        <div class="user-profile-thumb">
                            <img src="{{ $user->agentImage }}" alt="user">
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 form-group">
                        <ul class="user-profile-list">
                            <li class="bg--base one">{{ __("Full Name") }}: <span>{{ $user->fullname }}</span></li>
                            <li class="bg--info two">{{ __("Username") }}: <span>{{ "@".$user->username }}</span></li>
                            <li class="bg--success three">{{ __("Email") }}: <span>{{ $user->email }}</span></li>
                            <li class="bg--warning four">{{__("Status") }}: <span>{{ __($user->stringStatus->value) }}</span></li>
                            <li class="bg--danger five">{{ __("Last Login") }}: <span>{{ $user->lastLogin }}</span></li>
                        </ul>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="custom-card mt-15">
        <div class="card-header">
            <h6 class="title">{{ __("Information of Agent") }}</h6>
        </div>
        <div class="card-body">
            <form class="card-form" method="POST" action="{{ setRoute('admin.agents.details.update',$user->username) }}">
                @csrf
                <div class="row mb-10-none">
                    <div class="col-xl-6 col-lg-6 form-group">
                        @include('admin.components.form.input',[
                            'label'         => __("first Name")."*",
                            'name'          => "firstname",
                            'value'         => old("firstname",$user->firstname),
                            'attribute'     => "required",
                            'placeholder'   => __("Write Here.."),
                        ])
                    </div>
                    <div class="col-xl-6 col-lg-6 form-group">
                        @include('admin.components.form.input',[
                            'label'         => __("last Name")."*",
                            'name'          => "lastname",
                            'value'         => old("lastname",$user->lastname),
                            'attribute'     => "required",
                            'placeholder'   => __("Write Here.."),
                        ])
                    </div>
                    <div class="col-xl-6 col-lg-6 form-group">
                        <label>{{ __("phone Number") }}<span>*</span></label>
                        <div class="input-group">
                            <div class="input-group-text phone-code">+{{ $user->mobile_code }}</div>
                            <input class="phone-code" type="hidden" name="mobile_code" value="{{ $user->mobile_code }}" />
                            <input type="text" class="form--control" placeholder="{{ __("Write Here..") }}" name="mobile" value="{{ old('mobile',$user->mobile) }}">
                        </div>
                        @error("mobile")
                            <span class="invalid-feedback d-block" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="col-xl-6 col-lg-6 form-group">
                        @include('admin.components.form.input',[
                            'label'         => __("address"),
                            'name'          => 'address',
                            'value'         => old("address",$user->address->address ?? ""),
                            'placeholder'   => __("Write Here.."),
                        ])
                    </div>
                    <div class="col-xl-6 col-lg-6 form-group">
                        <label>{{ __("country") }}<span>*</span></label>
                        <select name="country" class="form--control select2-auto-tokenize country-select" data-placeholder="{{ __('select Country') }}" data-old="{{ old('country',$user->address->country ?? "") }}"></select>
                    </div>
                    <div class="col-xl-6 col-lg-6 form-group">
                        @php
                            $old_state = old('state',$user->address->state ?? "");
                        @endphp
                        <label>{{ __("state") }}</label>
                        <select name="state" class="form--control select2-auto-tokenize state-select" data-placeholder="Select State" data-old="{{ $old_state }}">
                            @if ($old_state)
                                <option value="{{ $old_state }}" selected>{{ $old_state }}</option>
                            @endif
                        </select>
                    </div>
                    <div class="col-xl-6 col-lg-6 form-group">
                        @php
                            $old_city = old('city',$user->address->city ?? "");
                        @endphp
                        <label>{{ __("city") }}</label>
                        <select name="city" class="form--control select2-auto-tokenize city-select" data-placeholder="Select City" data-old="{{ $old_city }}">
                            @if ($old_city)
                                <option value="{{ $old_city }}" selected>{{ $old_city }}</option>
                            @endif
                        </select>
                    </div>
                    <div class="col-xl-6 col-lg-6 form-group">
                        @include('admin.components.form.input',[
                            'label'         => __("Zip/Postal"),
                            'name'          => "zip_code",
                            'placeholder'   => __("Write Here.."),
                            'value'         => old('zip_code',$user->address->zip ?? "")
                        ])
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 form-group">
                        @include('admin.components.form.switcher', [
                            'label'         => __("Agent Status"),
                            'value'         => old('status',$user->status),
                            'name'          => "status",
                            'options'       => [__("active") => 1, __("banned") => 0],
                            'permission'    => "admin.agents.details.update",
                        ])
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 form-group">
                        @include('admin.components.form.switcher', [
                            'label'         => __("email Verification"),
                            'value'         => old('email_verified',$user->email_verified),
                            'name'          => "email_verified",
                            'options'       => [__("Verified") => 1, __("Unverified") => 0],
                            'permission'    => "admin.agents.details.update",
                        ])
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 form-group">
                        @include('admin.components.form.switcher', [
                            'label'     => __("2FA Verification"),
                            'value'     => old('two_factor_verified',$user->two_factor_verified),
                            'name'      => "two_factor_verified",
                             'options'       => [__("Verified") => 1, __("Unverified") => 0],
                            'permission'    => "admin.agents.details.update",
                        ])
                    </div>

                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 form-group">
                        @include('admin.components.form.switcher', [
                            'label'     => __("KYC Verification"),
                            'value'     => old('kyc_verified',$user->kyc_verified),
                            'name'      => "kyc_verified",
                             'options'       => [__("Verified") => 1, __("Unverified") => 0],
                            'permission'    => "admin.agents.details.update",
                        ])
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 form-group">
                        @include('admin.components.form.switcher', [
                            'label'         => __("Pin Setup Status"),
                            'value'         => old('pin_status',$user->pin_status),
                            'name'          => "pin_status",
                            'options'       => [__("Verified") => 1, __("Unverified") => 0],
                            'permission'    => "admin.agents.details.update",
                        ])
                    </div>

                    <div class="col-xl-12 col-lg-12 form-group mt-4">
                        @include('admin.components.button.form-btn',[
                            'text'          => __("update"),
                            'permission'    => "admin.agents.details.update",
                            'class'         => "w-100 btn-loading",
                        ])
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Send Email Modal --}}
    @include('admin.components.modals.send-mail-agent',compact("user"))
    @if (admin_permission_by_name("admin.agents.wallet.balance.update"))
        <div id="wallet-balance-update-modal" class="mfp-hide large">
            <div class="modal-data">
                <div class="modal-header px-0">
                    <h5 class="modal-title">{{ __("Add/Subtract Balance") }}</h5>
                </div>
                <div class="modal-form-data">
                    <form class="modal-form" method="POST" action="{{ setRoute('admin.agents.wallet.balance.update',$user->username) }}" enctype="multipart/form-data">
                        @csrf
                        <div class="row mb-10-none">
                            <div class="col-xl-12 col-lg-12 form-group">
                                <label for="balance">{{__("type")}}<span>*</span></label>
                                <select name="type" id="balance" class="form--control nice-select">
                                    <option disabled selected>{{ __("Select Type") }}</option>
                                    <option value="add">{{ __("Balance Add") }}</option>
                                    <option value="subtract">{{ __("Balance Subtract") }}</option>
                                </select>
                            </div>
                            <div class="col-xl-12 col-lg-12 form-group">
                                <label for="wallet">{{ __("User Wallet") }}<span>*</span></label>
                                <select name="wallet" id="wallet" class="form--control select2-auto-tokenize">
                                    <option disabled selected>{{ __("Select Agent Wallet") }}</option>
                                    @foreach ($user->wallet()->get() ?? [] as $item)
                                        <option value="{{ $item->id }}">{{ $item->currency->code }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-xl-12 col-lg-12 form-group">
                                @include('admin.components.form.input',[
                                    'label'         => __("Amount"),
                                    'label_after'   => "<span>*</span>",
                                    'type'          => 'text',
                                    'name'          => 'amount',
                                    'attribute'     => 'step="any"',
                                    'value'         => old("amount"),
                                    'placeholder'   =>  __("Write Here.."),
                                    'class'           =>  "number-input",
                                ])
                            </div>
                            <div class="col-xl-12 col-lg-12 form-group">
                                @include('admin.components.form.input',[
                                    'label'         => __("remark"),
                                    'label_after'   => "<span>*</span>",
                                    'name'          => "remark",
                                    'value'         => old("remark"),
                                     'placeholder'   =>  __("Write Here.."),
                                ])
                            </div>
                            <div class="col-xl-12 col-lg-12 form-group d-flex align-items-center justify-content-between mt-4">
                                <button type="button" class="btn btn--danger modal-close">{{ __("closeS") }}</button>
                                <button type="submit" class="btn btn--base">{{__("action")}}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endsection

@push('script')
    <script>
        getAllCountries("{{ setRoute('global.countries.agent') }}");
        $(document).ready(function() {

            openModalWhenError("email-send","#email-send");

            $("select[name=country]").change(function(){
                var phoneCode = $("select[name=country] :selected").attr("data-mobile-code");
                placePhoneCode(phoneCode);
            });

            setTimeout(() => {
                var phoneCodeOnload = $("select[name=country] :selected").attr("data-mobile-code");
                placePhoneCode(phoneCodeOnload);
            }, 400);

            countrySelect(".country-select",$(".country-select").siblings(".select2"));
            stateSelect(".state-select",$(".state-select").siblings(".select2"));


            $(".login-as-member").click(function() {
                var action  = "{{ setRoute('admin.agents.login.as.member',$user->username) }}";
                var target  = "{{ $user->username }}";
                postFormAndSubmit(action,target);
            });
        })
        $(".wallet-balance-update-btn").click(function(){
            openModalBySelector("#wallet-balance-update-modal");
        });
    </script>
@endpush
