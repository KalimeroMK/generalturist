<link rel="stylesheet" href="{{asset('libs/bootstrap4.0/css/bootstrap.min.css')}}">
<link rel="stylesheet" href="{{asset('module/media/css/browser.css?_ver='.config('app.asset_version'))}}">
<style>
    #cdn-browser-modal {
        opacity: 1;
        display: block;
    }

    #cdn-browser-modal .modal-dialog {
        width: 100%;
        margin: 0px;
        padding: 0px;
        max-width: none;
        transform: none;
    }

    #cdn-browser-modal .modal-content {
        padding: 0px;
        border: 0px;
        margin: 0px;
    }
</style>
<!-- CSRF Token -->
<meta name="csrf-token" content="{{ csrf_token() }}">
@include ('Media::browser')
<script>
    var bookingCore = {
        url: '{{url('/')}}',
        map_provider: '{{setting_item('map_provider')}}',
        map_gmap_key: '{{setting_item('map_gmap_key')}}'
    };

</script>
<script src="{{asset('libs/jquery-3.6.3.min.js')}}"></script>
<script src="{{asset('libs/vue/vue'.(!env('APP_DEBUG') ? '.min':'').'.js')}}"></script>
<script src="{{asset('libs/bootstrap4.0/js/bootstrap.min.js')}}"></script>
<script src="{{asset('module/media/js/browser.js?_ver='.config('app.asset_version'))}}"></script>
<script>
    (function ($) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#cdn-browser-modal').modal('show');
    })(jQuery)
</script>
