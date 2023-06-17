@if(!empty($translation->specs))
    <div class="g-specs">
        <h3> {{__("Specs & Details")}} </h3>
        <div class="list-item">
            @foreach($translation->specs as $item)
                <div class="item">
                    <div class="text">
                        <i class="fa fa-dot-circle-o" aria-hidden="true"></i> {{ $item['title']  }}: <strong>{{ $item['content'] }}</strong>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif