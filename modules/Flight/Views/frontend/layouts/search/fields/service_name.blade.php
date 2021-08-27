<div class="item">
    <span class="d-block text-gray-1  font-weight-normal mb-0 text-left">
        {{ $field['title'] ?? "" }}
    </span>
    <div class="mb-4">
        <div class="input-group border-bottom-1">
            <i class="flaticon-pin-1 d-flex align-items-center mr-2 text-primary font-weight-semi-bold font-size-22"></i>
            <input type="text" class="form-control font-weight-bold font-size-16 shadow-none hero-form font-weight-bold border-0 p-0 font-size-14" placeholder="{{__("Search for...")}}" value="{{ request()->input("service_name") }}">
        </div>
    </div>
</div>