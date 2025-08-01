
@php
    $details = json_decode(@$data->details);
@endphp
<div class="trx-input">
    <div class="row">
        <div class="col-xl-4 col-lg-4 col-md-6 form-group">
            <label>{{ __("country") }}<span>*</span></label>
            <select name="country" class="form--control country-select select2-basic " data-minimum-results-for-search="Infinity">
                <option selected disabled>{{ __("select Country") }}</option>
                @foreach ($countries as $item)
                 @if(get_default_currency_code() == $item->code && get_default_currency_name() == $item->country)
                    <option value="{{ $item->id }}" {{ $item->id == @$data->country?'selected':'' }} data-country-code="{{ $item->code }}" data-mobile-code="{{ $item->mobile_code }}"  data-id="{{ $item->id }}">{{ $item->country }} ({{ $item->code }})</option>
                 @endif
                @endforeach
            </select>
        </div>
        <div class="col-xl-4 col-lg-4 col-md-6 form-group">
            <label>{{ __("phone Number") }}<span>*</span></label>
            <div class="input-group">
              <div class="input-group-text phone-code">+{{ @$data->mobile_code }}</div>
              <input class="phone-code" type="hidden" name="mobile_code" value="{{ @$data->mobile_code }}"/>
              <input type="text" class="form--control mobile" placeholder="{{ __("enter Mobile Number") }}" name="mobile" value="{{ @$data->mobile }}">
            </div>
        </div>
        <div class="col-xl-4 col-lg-4 col-md-6 form-group">
            <label>{{ __("email Address") }}<span>*</span></label>
              <div class="input-group">
                <input type="email" class="form--control email" placeholder="{{ __('enter Email Address') }}" name="email" value="{{ @$data->email }}">
              </div>
         </div>
        <div class="col-xl-4 col-lg-4 col-md-6 form-group">
            @include('admin.components.form.input',[
                'name'          => "firstname",
                'label'         => __("first Name"),
                'label_after'   => "<span>*</span>",
               'placeholder'         => __("first Name"),
                'attribute'     => "readonly",
                'value'     => @$data->firstname,
            ])
        </div>

        <div class="col-xl-4 col-lg-4 col-md-6 form-group">
            @include('admin.components.form.input',[
                 'label'         => __("last Name"),
                'label_after'   => "<span>*</span>",
                'name'          => "lastname",
                 'placeholder'         => __("last Name"),
                'attribute'     => "readonly",
                'value'     => @$data->lastname,
            ])
        </div>

        <div class="col-xl-4 col-lg-4 col-md-6 form-group state-select-wrp">
            @include('admin.components.form.input',[
              'label'         => __("address"),
                'label_after'   => "<span>*</span>",
                'name'          => "address",
               'placeholder'         => __("enter Address"),
                'attribute'     => "readonly id=place-input autocomplete=none",
                'value'     => @$data->address,
            ])
        </div>
        <div class="col-xl-4 col-lg-4 col-md-6 form-group state-select-wrp">
            @include('admin.components.form.input',[
               'label'         => __("state"),
                'name'          => "state",
                'placeholder'         => __("enter State"),
                'attribute'     => "readonly",
                'value'     => @$data->state,
            ])
        </div>
        <div class="col-xl-4 col-lg-4 col-md-6 form-group city-select-wrp">
            @include('admin.components.form.input',[
              'label'         => __("city"),
                'label_after'   => "<span>*</span>",
                'name'          => "city",
               'placeholder'         => __("enter City"),
                'attribute'     => "readonly",
                'value'     => @$data->city,
            ])
        </div>
        <div class="col-xl-4 col-lg-4 col-md-6 form-group">
            @include('admin.components.form.input',[
                'label'         => __("zip Code"),
                'label_after'   => "<span>*</span>",
                'name'          => "zip",
                'type'          => "text",
                'placeholder'         => __("zip Code"),
                'attribute'     => "readonly",
                'value'     => @$data->zip_code,
            ])
        </div>

    </div>
</div>
