@if(count($flight_related) > 0)
    <div class="bravo-list-space-related product-card-carousel-block product-card-carousel-v5 mb-3">
        <div class="space-1">
            <div class="w-md-80 w-lg-50 text-center mx-md-auto mt-3">
                <h2 class="section-title text-black font-size-30 font-weight-bold mb-0">{{ __("You might also like...") }}</h2>
            </div>
            <div class="travel-slick-carousel u-slick u-slick--equal-height u-slick--gutters-3"
                 data-slides-show="4"
                 data-slides-scroll="1"
                 data-arrows-classes="d-none d-xl-inline-block u-slick__arrow-classic v1 u-slick__arrow-classic--v1 u-slick__arrow-centered--y rounded-circle"
                 data-arrow-left-classes="fa fa-chevron-left u-slick__arrow-classic-inner u-slick__arrow-classic-inner--left shadow-5"
                 data-arrow-right-classes="fa fa-chevron-right u-slick__arrow-classic-inner u-slick__arrow-classic-inner--right shadow-5"
                 data-pagi-classes="text-center d-xl-none u-slick__pagination mt-4"
                 data-responsive='[{
                            "breakpoint": 1025,
                            "settings": {
                            "slidesToShow": 3
                            }
                            }, {
                            "breakpoint": 992,
                            "settings": {
                            "slidesToShow": 2
                            }
                            }, {
                            "breakpoint": 768,
                            "settings": {
                            "slidesToShow": 1
                            }
                            }, {
                            "breakpoint": 554,
                            "settings": {
                            "slidesToShow": 1
                            }
                            }]'>
                @foreach($flight_related as $k=>$item)
                    <div class="js-slide mt-5">
                        @include('Flight::frontend.layouts.search.loop-grid',['row'=>$item,'include_param'=>0])
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endif