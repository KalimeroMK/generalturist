@extends('admin.layouts.app')
@section('content')
    <div class="container">
        <div class="mb40">
            <div class="d-flex justify-content-between">
                <h1 class="title-bar">{{$group['name']}}</h1>
            </div>
            <hr>
        </div>
        @include('admin.message')
        <div class="row">
            <div class="col-md-3 d-none">
                <div class="panel">
                    <div class="panel-title">{{__('Settings Groups')}}</div>
                    <div class="panel-body">
                        <ul class="panel-navs">
                            @foreach($groups as $k=>$row)
                                <li class="@if($current_group == $k) active @endif"><a
                                            href="{{route('core.admin.settings.index',['group'=>$k])}}">
                                        @if($row['icon'])
                                            <i class="{{$row['icon']}}"></i>
                                        @endif
                                        {{$row['name']}}
                                    </a></li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <form action="{{route('core.admin.settings.store',['group'=>$current_group])}}" method="post"
                      autocomplete="off">
                    @csrf

                    @include('Language::admin.navigation')

                    <div class="lang-content-box">
                        @if(empty($group['view']))
                            @include ('Core::admin.settings.groups.'.$current_group)
                        @else
                            @include ($group['view'])
                        @endif
                    </div>

                    <hr>
                    <div class="d-flex justify-content-between">
                        <span></span>
                        <button class="btn btn-primary" type="submit">{{__('Save settings')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
