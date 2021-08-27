@php
    $terms_ids = $row->terms->pluck('term_id');
    $attributes = \Modules\Core\Models\Terms::getTermsById($terms_ids);
@endphp
@if(!empty($terms_ids) and !empty($attributes))
    @foreach($attributes as $attribute )
        @php $translate_attribute = $attribute['parent']->translateOrOrigin(app()->getLocale()) @endphp
        @if(empty($attribute['parent']['hide_in_single']))
            <div class="list-attributes border-bottom py-4 {{$attribute['parent']->slug}} attr-{{$attribute['parent']->id}}">
                <h3 class="font-size-21 font-weight-bold text-dark mb-4">{{ $translate_attribute->name }}</h3>
                @php $terms = $attribute['child'] @endphp
                <ul class="list-group list-group-borderless list-group-horizontal list-group-flush no-gutters row">
                    @foreach($terms as $term )
                        @php $translate_term = $term->translateOrOrigin(app()->getLocale()) @endphp
                        <li class="col-md-4 mb-3 list-group-item item {{$term->slug}} term-{{$term->id}}">
                            @if(!empty($term->image_id))
                                @php $image_url = get_file_url($term->image_id, 'full'); @endphp
                                <img src="{{$image_url}}" class="img-responsive" alt="{{$translate_term->name}}">
                            @else
                                <i class="mr-3 text-primary font-size-24 {{ $term->icon ?? "icofont-check-circled icon-default" }}"></i>
                            @endif
                            {{$translate_term->name}}
                        </li>

                    @endforeach
                </ul>
            </div>
        @endif
    @endforeach
@endif