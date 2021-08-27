<div class="sidebar border border-color-1 rounded-xs">
    <div class="p-4 mb-1">
        <form action="{{ route("flight.search") }}" class="bravo_form" method="get">
            @php $search_fields = setting_item_array('flight_search_fields');
            $search_fields = array_values(\Illuminate\Support\Arr::sort($search_fields, function ($value) {
                return $value['position'] ?? 0;
            }));
            @endphp
            @if(!empty($search_fields))
                @foreach($search_fields as $field)
                    @php $field['title'] = $field['title_'.app()->getLocale()] ?? $field['title'] ?? "" @endphp
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

                        @case ('attr')
                            @include('Flight::frontend.layouts.search.fields.attr')
                        @break
                        @case ('guests')
                          @include('Flight::frontend.layouts.search.fields.guests')
                        @break
                    @endswitch
                @endforeach
            @endif
            <div class="text-center">
                <button type="submit" class="btn btn-primary height-60 w-100 font-weight-bold mb-xl-0 mb-lg-1 transition-3d-hover"><i class="flaticon-magnifying-glass mr-2 font-size-17"></i>Search</button>
            </div>
        </form>
    </div>
</div>