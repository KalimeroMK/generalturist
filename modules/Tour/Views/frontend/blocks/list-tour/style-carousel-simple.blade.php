<div class="container">
    <div class="row">
        <div class="col-md-3 align-self-center">
            @if($title)
                <div class="title">
                    {{$title}}
                </div>
            @endif
            @if(!empty($desc))
                <div class="sub-title">
                    {{$desc}}
                </div>
            @endif
        </div>
        <div class="col-md-9">
            <div class="list-item">
                <div class="owl-carousel">
                    @foreach($rows as $row)
                        @php
                            $translation = $row->translateOrOrigin(app()->getLocale());
                        @endphp
                        <div class="item-tour {{$wrap_class ?? ''}}">
                            @if($row->discount_percent)
                                <div class="sale_info">{{$row->discount_percent}}</div>
                            @endif
                            @if($row->is_featured == "1")
                                <div class="featured">
                                    {{__("Featured")}}
                                </div>
                            @endif
                            <div class="thumb-image">
                                <a @if(!empty($blank)) target="_blank" @endif href="{{$row->getDetailUrl($include_param ?? true)}}">
                                    @if($row->image_url)
                                        @if(!empty($disable_lazyload))
                                            <img src="{{$row->image_url}}" class="img-responsive" alt="{{$location->name ?? ''}}">
                                        @else
                                            {!! get_image_tag($row->image_id,'medium',['class'=>'img-responsive','alt'=>$row->title]) !!}
                                        @endif
                                    @endif
                                </a>
                                <div class="service-wishlist {{$row->isWishList()}}" data-id="{{$row->id}}" data-type="{{$row->type}}">
                                    <i class="fa fa-heart"></i>
                                </div>
                            </div>
                            <div class="price">
                                <span class="onsale">{{ $row->display_sale_price }}</span>
                                <span class="text-price"> <span class="small">from</span> {{ $row->display_price }}</span>
                            </div>
                        </div>



                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>