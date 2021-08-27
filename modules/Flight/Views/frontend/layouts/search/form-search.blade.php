<form action="{{ route("flight.search") }}" class="form bravo_form" method="get">
    <div class="g-field-search">
        <div class="row ">
            @php $flight_search_fields = setting_item_array('flight_search_fields');
    $flight_search_fields = array_values(\Illuminate\Support\Arr::sort($flight_search_fields, function ($value) {
        return $value['position'] ?? 0;
    }));
            @endphp
            @if(!empty($flight_search_fields))
                @foreach($flight_search_fields as $field)
                    @php $field['title'] = $field['title_'.app()->getLocale()] ?? $field['title'] ?? "" @endphp
                    <div class="col-md-{{ $field['size'] ?? "6" }} border-right">
                        @switch($field['field'])
                            @case ('service_name')
                            @include('Flight::frontend.layouts.search.fields.service_name')
                            @break
                            @case ('location')
                            @include('Flight::frontend.layouts.search.fields.location')
                            @break
                            @case ('date')
                            @include('Flight::frontend.layouts.search.fields.date')
                            @break
                            @case ('guests')
                            @include('Flight::frontend.layouts.search.fields.guests')
                            @break
                            @case ('seat_type')
                            @include('Flight::frontend.layouts.search.fields.seat_type')
                            @break
                            @case ('from_where')
                            @include('Flight::frontend.layouts.search.fields.from-where')
                            @break
                            @case ('to_where')
                            @include('Flight::frontend.layouts.search.fields.to-where')
                            @break
                        @endswitch
                    </div>
                @endforeach
            @endif
        </div>
    </div>

    <div class="g-button-submit">
        <button class="btn btn-primary btn-search" type="submit">{{__("Search")}}</button>
    </div>
</form>
