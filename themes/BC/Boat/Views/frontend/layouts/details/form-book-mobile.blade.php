<div class="bravo-more-book-mobile">
    <div class="container">
        <div class="left">
            <div class="g-price">
                <div class="prefix">
                    <span class="fr_text">{{__("from")}}</span>
                </div>
                <div class="price ml-1">
                    <div><strong>{{ format_money($row->price_per_hour) }}</strong><small>{{ __("/per hour") }}</small></div>
                    <div><strong>{{ format_money($row->price_per_day) }}</strong><small>{{ __("/per day") }}</small></div>
                </div>
            </div>
        </div>
        <div class="right">
            @if($row->getBookingEnquiryType() === "book")
                <a class="btn btn-primary bravo-button-book-mobile">{{__("Book Now")}}</a>
            @else
                <a class="btn btn-primary" data-toggle="modal" data-target="#enquiry_form_modal">{{__("Contact Now")}}</a>
            @endif
        </div>
    </div>
</div>
