@php
    $translation = $row->translate();
@endphp
<div class="item-loop {{$wrap_class ?? ''}}">
    @if($row->is_featured == "1")
        <div class="featured">
            {{__("Featured")}}
        </div>
    @endif
    <div class="thumb-image ">
        <a @if(!empty($blank)) target="_blank" @endif href="{{$row->getDetailUrl($include_param ?? true)}}">
            @if($row->image_url)
                @if(!empty($disable_lazyload))
                    <img src="{{$row->image_url}}" class="img-responsive" alt="">
                @else
                    {!! get_image_tag($row->image_id,'medium',['class'=>'img-responsive','alt'=>$row->title]) !!}
                @endif
            @endif
        </a>
        <div class="service-wishlist {{$row->isWishList()}}" data-id="{{$row->id}}" data-type="{{$row->type}}">
            <i class="fa fa-heart-o"></i>
        </div>
    </div>
    <div class="location">
        @if(!empty($row->location->name))
            @php $location =  $row->location->translate() @endphp
            {{$location->name ?? ''}}
        @endif
    </div>
    <div class="item-title">
        <a @if(!empty($blank)) target="_blank" @endif href="{{$row->getDetailUrl($include_param ?? true)}}">
            @if($row->is_instant)
                <i class="fa fa-bolt d-none"></i>
            @endif
                {{$translation->title}}
        </a>
    </div>
        @if(setting_item('boat_enable_review'))
            <?php
            $reviewData = $row->getScoreReview();
            $score_total = $reviewData['score_total'];
            ?>
            <div class="service-review">
                <span class="rate">
                    @if($reviewData['total_review'] > 0) {{$score_total}}/5 @endif <span class="rate-text">{{$reviewData['review_text']}}</span>
                </span>
                <span class="review">
                 @if($reviewData['total_review'] > 1)
                        {{ __(":number Reviews",["number"=>$reviewData['total_review'] ]) }}
                    @else
                        {{ __(":number Review",["number"=>$reviewData['total_review'] ]) }}
                    @endif
                </span>
            </div>
        @endif
    <div class="amenities">
        @if($row->max_guest)
            <span class="amenity total" data-toggle="tooltip"  title="{{ __("Max Guests") }}">
                <i class="icofont-ui-user-group input-icon field-icon"></i>
                <span class="text">
                    {{$row->max_guest}}
                </span>
            </span>
        @endif
        @if($row->cabin)
            <span class="amenity bed" data-toggle="tooltip" title="{{__("Cabin")}}">
                <i class="input-icon field-icon icofont-sail-boat-alt-2"></i>
                <span class="text">
                    {{$row->cabin}}
                </span>
            </span>
        @endif
        @if($row->length)
            <span class="amenity bath" data-toggle="tooltip" title="{{__("Length Boat")}}" >
                <i class="input-icon field-icon icofont-yacht"></i>
                <span class="text">
                    {{$row->length}}
                </span>
            </span>
        @endif
        @if($row->speed)
            <span class="amenity size" data-toggle="tooltip" title="{{__("Speed")}}" >
                <i class="input-icon field-icon icofont-speed-meter"></i>
                <span class="text">
                    {{$row->speed}}
                </span>
            </span>
        @endif
    </div>
    <div class="info">
        <div class="g-price">
            <div class="prefix">
                <span class="fr_text">{{__("from")}}</span>
            </div>
            <div class="price">
                <span class="text-price">{{ format_money($row->min_price) }}</span>
            </div>
        </div>
    </div>
</div>
