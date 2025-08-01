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
            @if ($customer_card  < $card_limit )
                <a href="{{ setRoute('user.strowallet.virtual.card.create') }}" class="btn--base small" >{{ __("create Card") }} <i class="las la-plus ms-1"></i></a>
            @endif
        </div>
    </div>
    <div class="row mb-30-none justify-content-center">
        <div class="col-xxl-5 col-xl-6 col-lg-6 col-md-8 overflow-hidden  mb-30">
            <div class="virtualCard-slider-wrapper">
                <div class="virtualCard-slider">
                    <div class="swiper-wrapper">
                        @forelse ($myCards ?? []  as $index => $myCard)
                        <div class="swiper-slide">
                            <div class="dash-payment-item-wrapper">
                                <div class="dash-payment-item active">
                                    <div class="card-header-btn-wrapper d-flex align-items-center justify-content-between">
                                        <div class="dash-payment-title-area">
                                            <span class="dash-payment-badge">!</span>
                                            <h5 class="title">{{ __("My Card") . " (" . ($index+1) . "/" . $cardApi->card_limit . ")" }}</h5>
                                        </div>
                                        @php
                                            $live_card_data = card_details($myCard->card_id,$card_api->config->strowallet_public_key,$card_api->config->strowallet_url);
                                        @endphp
                                        <a href="javascript:void(0)" class="small--btn">{{ __("balance") }}:
                                             {{ updateStroWalletCardBalance(auth()->user(),$myCard->card_id,$live_card_data)." ".$myCard->currency }}
                                         </a>
                                    </div>
                                    <div class="virtual-card-wrapper d-flex justify-content-center">
                                        <div class="dash-payment-body">
                                            <div class="card-custom">
                                                <div class="flip">
                                                    <div class="front bg_img" data-background="{{ get_image(@$cardApi->image ,'card-api') }}">
                                                         <img class="logo" src="{{ get_fav($basic_settings,'dark') }}"
                                                            alt="site-logo">
                                                        <div class="investor">{{ @$basic_settings->site_name }}</div>
                                                        <div class="chip">
                                                            <div class="chip-line"></div>
                                                            <div class="chip-line"></div>
                                                            <div class="chip-line"></div>
                                                            <div class="chip-line"></div>
                                                            <div class="chip-main"></div>
                                                        </div>
                                                        <svg class="wave" viewBox="0 3.71 26.959 38.787" width="26.959" height="38.787" fill="white">
                                                            <path d="M19.709 3.719c.266.043.5.187.656.406 4.125 5.207 6.594 11.781 6.594 18.938 0 7.156-2.469 13.73-6.594 18.937-.195.336-.57.531-.957.492a.9946.9946 0 0 1-.851-.66c-.129-.367-.035-.777.246-1.051 3.855-4.867 6.156-11.023 6.156-17.718 0-6.696-2.301-12.852-6.156-17.719-.262-.317-.301-.762-.102-1.121.204-.36.602-.559 1.008-.504z"></path>
                                                            <path d="M13.74 7.563c.231.039.442.164.594.343 3.508 4.059 5.625 9.371 5.625 15.157 0 5.785-2.113 11.097-5.625 15.156-.363.422-1 .472-1.422.109-.422-.363-.472-1-.109-1.422 3.211-3.711 5.156-8.551 5.156-13.843 0-5.293-1.949-10.133-5.156-13.844-.27-.309-.324-.75-.141-1.114.188-.367.578-.582.985-.542h.093z"></path>
                                                            <path d="M7.584 11.438c.227.031.438.144.594.312 2.953 2.863 4.781 6.875 4.781 11.313 0 4.433-1.828 8.449-4.781 11.312-.398.387-1.035.383-1.422-.016-.387-.398-.383-1.035.016-1.421 2.582-2.504 4.187-5.993 4.187-9.875 0-3.883-1.605-7.372-4.187-9.875-.321-.282-.426-.739-.266-1.133.164-.395.559-.641.984-.617h.094zM1.178 15.531c.121.02.238.063.344.125 2.633 1.414 4.437 4.215 4.437 7.407 0 3.195-1.797 5.996-4.437 7.406-.492.258-1.102.07-1.36-.422-.257-.492-.07-1.102.422-1.359 2.012-1.075 3.375-3.176 3.375-5.625 0-2.446-1.371-4.551-3.375-5.625-.441-.204-.676-.692-.551-1.165.122-.468.567-.785 1.051-.742h.094z"></path>
                                                        </svg>

                                                        @if ($myCard->card_number)
                                                            @php
                                                                $card_pan = str_split($myCard->card_number, 4);
                                                            @endphp
                                                            <div class="card-number">
                                                                @foreach($card_pan as $key => $value)
                                                                    <div class="section">{{ $value }}</div>
                                                                @endforeach
                                                            </div>
                                                        @else
                                                            <div class="card-number">
                                                                <div class="section">----</div>
                                                                <div class="section">----</div>
                                                                <div class="section">----</div>
                                                                <div class="section">----</div>
                                                            </div>
                                                        @endif
                                                        <div class="end">
                                                            <span class="end-text">{{ __("exp. end:") }}</span> <span class="end-date"> {{  $myCard->expiry ?? 'mm/yyyy' }}</span>
                                                        </div>
                                                        <div class="card-holder"> {{ $myCard->name_on_card??auth()->user()->fullname }}</div>
                                                    </div>
                                                    <div class="back">
                                                        <div class="strip-black"></div>
                                                        <div class="ccv">
                                                            <label>{{ __("Cvv") }}</label>
                                                            <div>{{$myCard->cvv ?? '---' }}</div>

                                                        </div>
                                                        <div class="terms">
                                                            @php
                                                                echo @$cardApi->card_details;
                                                            @endphp
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="virtual-card-btn-area text-center pt-20">
                                        <a href="{{ @$myCard != null ? setRoute('user.strowallet.virtual.card.details',$myCard->card_id) : 'javascript:void(0)'  }}" class="btn--base"><i class="fas fa-info-circle me-1"></i>{{ __("Details") }}</a>
                                        @if($myCard->is_default == true )
                                        <a href="javascript:void(0)" class="btn--base active-deactive-btn" data-id="{{ $myCard->id }}"><i class="fas fa-times-circle me-1"></i>{{ __("remove Default") }}</a>
                                        @else
                                        <a href="javascript:void(0)" class="btn--base active-deactive-btn" data-id="{{ $myCard->id }}"><i class="fas fa-check-circle me-1"></i>{{ __("make Default") }}</a>
                                        @endif

                                        <a href="{{ setRoute('user.strowallet.virtual.card.fund.page',$myCard->id) }}" class="btn--base" data-id="{{ $myCard->id }}" data-card-currency="{{ $myCard->currency }}"><i class="fas fa-hand-holding-usd me-1"></i> {{ __("fund") }}</a>
                                        <a href="{{ @$myCard != null ? setRoute('user.strowallet.virtual.card.transaction',$myCard->card_id) : 'javascript:void(0)'  }}" class="btn--base"><i class="fas fa-arrows-alt-h me-1"></i>{{ __("Transactions") }}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="swiper-slide">
                            <div class="dash-payment-item-wrapper">
                                <div class="dash-payment-item active">
                                    <div class="card-header-btn-wrapper d-flex align-items-center justify-content-between">
                                        <div class="dash-payment-title-area">
                                            <span class="dash-payment-badge">!</span>
                                            <h5 class="title"> {{ __("My Card")." (" }}{{ "0"."/".$cardApi->card_limit.")"}}</h5>
                                        </div>

                                    </div>
                                    <div class="virtual-card-wrapper d-flex justify-content-center">
                                        <div class="dash-payment-body">
                                            <div class="card-custom">
                                                <div class="flip">
                                                    <div class="front bg_img" data-background="{{ get_image(@$cardApi->image ,'card-api') }}">
                                                         <img class="logo" src="{{ get_fav($basic_settings,'dark') }}"
                                                            alt="site-logo">
                                                        <div class="investor">{{ @$basic_settings->site_name }}</div>
                                                        <div class="chip">
                                                            <div class="chip-line"></div>
                                                            <div class="chip-line"></div>
                                                            <div class="chip-line"></div>
                                                            <div class="chip-line"></div>
                                                            <div class="chip-main"></div>
                                                        </div>
                                                        <svg class="wave" viewBox="0 3.71 26.959 38.787" width="26.959" height="38.787" fill="white">
                                                            <path d="M19.709 3.719c.266.043.5.187.656.406 4.125 5.207 6.594 11.781 6.594 18.938 0 7.156-2.469 13.73-6.594 18.937-.195.336-.57.531-.957.492a.9946.9946 0 0 1-.851-.66c-.129-.367-.035-.777.246-1.051 3.855-4.867 6.156-11.023 6.156-17.718 0-6.696-2.301-12.852-6.156-17.719-.262-.317-.301-.762-.102-1.121.204-.36.602-.559 1.008-.504z"></path>
                                                            <path d="M13.74 7.563c.231.039.442.164.594.343 3.508 4.059 5.625 9.371 5.625 15.157 0 5.785-2.113 11.097-5.625 15.156-.363.422-1 .472-1.422.109-.422-.363-.472-1-.109-1.422 3.211-3.711 5.156-8.551 5.156-13.843 0-5.293-1.949-10.133-5.156-13.844-.27-.309-.324-.75-.141-1.114.188-.367.578-.582.985-.542h.093z"></path>
                                                            <path d="M7.584 11.438c.227.031.438.144.594.312 2.953 2.863 4.781 6.875 4.781 11.313 0 4.433-1.828 8.449-4.781 11.312-.398.387-1.035.383-1.422-.016-.387-.398-.383-1.035.016-1.421 2.582-2.504 4.187-5.993 4.187-9.875 0-3.883-1.605-7.372-4.187-9.875-.321-.282-.426-.739-.266-1.133.164-.395.559-.641.984-.617h.094zM1.178 15.531c.121.02.238.063.344.125 2.633 1.414 4.437 4.215 4.437 7.407 0 3.195-1.797 5.996-4.437 7.406-.492.258-1.102.07-1.36-.422-.257-.492-.07-1.102.422-1.359 2.012-1.075 3.375-3.176 3.375-5.625 0-2.446-1.371-4.551-3.375-5.625-.441-.204-.676-.692-.551-1.165.122-.468.567-.785 1.051-.742h.094z"></path>
                                                        </svg>

                                                        <div class="card-number">
                                                            <div class="section">0000</div>
                                                            <div class="section">0000</div>
                                                            <div class="section">0000</div>
                                                            <div class="section">0000</div>
                                                        </div>
                                                        <div class="end">
                                                            <span class="end-text">exp. end:</span><span class="end-date"> 00/00</span>
                                                        </div>
                                                        <div class="card-holder"> {{ auth()->user()->fullname }}</div>
                                                    </div>
                                                    <div class="back">
                                                        <div class="strip-black"></div>
                                                        <div class="ccv">
                                                            <label>{{ __("Cvv") }}</label>
                                                            <div>{{ __("***") }}</div>

                                                        </div>
                                                        <div class="terms">
                                                            @php
                                                                echo @$cardApi->card_details;
                                                            @endphp
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforelse
                    </div>
                    <div class="slider-nav-area">
                        <div class="slider-prev slider-nav mt-4">
                            <i class="las la-angle-left"></i>
                        </div>
                        <div class="slider-next slider-nav mt-4">
                            <i class="las la-angle-right"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="dashboard-list-area mt-20">
        <div class="dashboard-header-wrapper">
            <h4 class="title ">{{__("recent Transactions")}}</h4>
            <div class="dashboard-btn-wrapper">
                <div class="dashboard-btn mb-2">
                    <a href="{{ setRoute('user.transactions.index','virtual-card') }}" class="btn--base">{{__("View More")}}</a>
                </div>
            </div>
        </div>
        <div class="dashboard-list-wrapper">
            @include('user.components.transaction-log',compact("transactions"))
        </div>
    </div>
</div>

@endsection

@push('script')

<script>
    var swiper = new Swiper('.virtualCard-slider', {
        spaceBetween: 30,
        loop: false,
        centeredSlides: true,
        navigation: {
        nextEl: '.slider-next',
        prevEl: '.slider-prev',
        },
        speed: 500,
    });
    $(".active-deactive-btn").click(function(){
        var actionRoute =  "{{ setRoute('user.strowallet.virtual.card.make.default.or.remove') }}";
        var target = $(this).data('id');
        var btnText = $(this).text();
        var sureText = '{{ __("Are you sure to") }}';
        var message     = `${sureText} <strong>${btnText}</strong>?`;
        openAlertModal(actionRoute,target,message,btnText,"POST");
    });
</script>
@endpush
