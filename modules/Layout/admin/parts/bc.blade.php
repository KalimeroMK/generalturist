@if(!empty($breadcrumbs))
    <nav class="main-breadcrumb" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('admin.index')}}"><i
                            class='fa fa-home'></i> {{__("Dashboard")}}</a></li>

            @foreach($breadcrumbs as $breadcrumb)
                <li class="breadcrumb-item {{$breadcrumb['class'] ?? ''}}">
                    @if(!empty($breadcrumb['url']))
                        <a href="{{url($breadcrumb['url'])}}">{{$breadcrumb['name']}}</a>
                    @else
                        {{$breadcrumb['name']}}
                    @endif
                </li>
            @endforeach

        </ol>
    </nav>
@endif
