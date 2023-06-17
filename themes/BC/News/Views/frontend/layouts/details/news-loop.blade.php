@foreach($rows as $row)
    @php
        $translation = $row->translate(); @endphp
    <div class="post_item ">
        <div class="header">
            @if($image_tag = get_image_tag($row->image_id,'full',['alt'=>$translation->title]))
                <header class="post-header">
                    <a href="{{$row->getDetailUrl()}}">
                        {!! $image_tag !!}
                    </a>
                </header>
                <div class="cate">
                    @php $category = $row->category; @endphp
                    @if(!empty($category))
                        @php $t = $category->translate(); @endphp
                        <ul>
                            <li>
                                <a href="{{$category->getDetailUrl(app()->getLocale())}}">
                                    {{$t->name ?? ''}}
                                </a>
                            </li>
                        </ul>
                    @endif
                </div>
            @endif
            <div class="post-inner">
                <h3 class="post-title">
                    <a class="text-darken" href="{{$row->getDetailUrl()}}"> {{$translation->title}}</a>
                </h3>
                <div class="post-info">
                    <ul>
                        @if(!empty($row->author))
                            <li>
                                @if($avatar_url = $row->author->getAvatarUrl())
                                    <img class="avatar" src="{{$avatar_url}}" alt="{{$row->author->getDisplayName()}}">
                                @else
                                    <span class="avatar-text">{{ucfirst($row->author->getDisplayName()[0])}}</span>
                                @endif
                                <span> {{ __('BY ')}} </span>
                                {{$row->author->getDisplayName() ?? ''}}
                            </li>
                        @endif
                        <li> {{__('DATE ')}}  {{ display_date($row->updated_at)}}  </li>
                    </ul>
                </div>
                <div class="post-desciption">
                    {!! get_exceprt($translation->content) !!}
                </div>
                <a class="btn-readmore" href="{{$row->getDetailUrl()}}">{{ __('Read More')}}</a>
            </div>
        </div>
    </div>
@endforeach
