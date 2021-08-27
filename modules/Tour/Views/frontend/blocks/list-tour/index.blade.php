<div class="bravo-list-tour {{$style_list}}">
    @if(in_array($style_list,['normal','carousel','box_shadow']))
        @include("Tour::frontend.blocks.list-tour.style-normal")
    @endif
    @if($style_list == "carousel_simple")
        @include("Tour::frontend.blocks.list-tour.style-carousel-simple")
    @endif
</div>