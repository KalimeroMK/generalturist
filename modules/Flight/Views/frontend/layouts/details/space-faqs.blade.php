@if($translation->faqs)
    <div class="border-bottom py-4">
        <h5 class="font-size-21 font-weight-bold text-dark mb-4">
            {{__("FAQs")}}
        </h5>
        <div id="FAQs">
            @foreach($translation->faqs as $key=>$item)
                <div class="card border-0 mb-4 pb-1">
                    <div class="card-header border-bottom-0 p-0" >
                        <h5 class="mb-0">
                            <button type="button" class="collapse-link btn btn-link btn-block d-flex align-items-md-center p-0" data-toggle="collapse" data-target="#FAQs_{{$key}}" @if($key == 0 ) aria-expanded="true" @else aria-expanded="false" @endif aria-controls="FAQs_{{$key}}">
                                <div class="border border-color-8 rounded-xs border-width-2 p-2 mb-3 mb-md-0 mr-4">
                                    <figure id="rectangle" class="minus">
                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="16px" height="2px" class="injected-svg js-svg-injector mb-0" data-parent="#rectangle">
                                            <path fill-rule="evenodd" fill="rgb(59, 68, 79)" d="M-0.000,-0.000 L15.000,-0.000 L15.000,2.000 L-0.000,2.000 L-0.000,-0.000 Z"></path>
                                        </svg>
                                    </figure>
                                    <figure id="plus1" class="plus">
                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="16px" height="16px" class="injected-svg js-svg-injector mb-0" data-parent="#plus1">
                                            <path fill-rule="evenodd" fill="rgb(59, 68, 79)" d="M16.000,9.000 L9.000,9.000 L9.000,16.000 L7.000,16.000 L7.000,9.000 L-0.000,9.000 L-0.000,7.000 L7.000,7.000 L7.000,-0.000 L9.000,-0.000 L9.000,7.000 L16.000,7.000 L16.000,9.000 Z"></path>
                                        </svg>
                                    </figure>
                                </div>
                                <h6 class="font-weight-bold text-gray-3 mb-0">{{$item['title']}}</h6>
                            </button>
                        </h5>
                    </div>
                    <div id="FAQs_{{$key}}" class="collapse @if($key == 0 ) show @endif" data-parent="#FAQs">
                        <div class="card-body">
                            <p class="mb-0 text-gray-1 text-lh-lg">
                                {!! clean($item['content']) !!}
                            </p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif