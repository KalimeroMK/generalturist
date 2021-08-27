<div class="border-bottom py-4">
    <h5 class="font-size-21 font-weight-bold text-dark mb-4">
        {{ __("Video") }}
    </h5>
    <!-- Video Block -->
    <div id="youTubeVideoPlayer" class="u-video-player rounded-sm">
        <!-- Cover Image -->
        <img class="img-fluid u-video-player__preview rounded-sm" src="{{ $row->getBannerImageUrlAttribute('full')}} " alt="Image">
        <!-- End Cover Image -->
        <!-- Play Button -->
        <a class="travel-inline-video-player u-video-player__btn u-video-player__centered" href="javascript:;"
           data-video-id="{{ handleVideoUrl($row->video,true) }}"
           data-parent="youTubeVideoPlayer"
           data-is-autoplay="true"
           data-target="youTubeVideoIframe"
           data-classes="u-video-player__played">
            <span class="u-video-player__icon u-video-player__icon--lg text-primary bg-transparent">
                <span class="flaticon-multimedia text-white ml-0 font-size-60 u-video-player__icon-inner"></span>
            </span>
        </a>
        <!-- End Play Button -->

        <!-- Video Iframe -->
        <div class="embed-responsive embed-responsive-16by9 rounded-sm">
            <div id="youTubeVideoIframe"></div>
        </div>
        <!-- End Video Iframe -->
    </div>
    <!-- End Video Block -->
</div>