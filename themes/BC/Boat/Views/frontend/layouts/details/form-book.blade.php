<div class="bravo_single_book_wrap">
    <div class="bravo_single_book">
        <div id="bravo_boat_book_app" v-cloak>
            <div class="form-head">
                <div class="price flex-wrap">
                    <span class="value">
                        <div>{{ format_money($row->price_per_hour) }}<small>{{ __("/per hour") }}</small></div>
                        <div>{{ format_money($row->price_per_day) }}<small>{{ __("/per day") }}</small></div>
                    </span>
                </div>
            </div>
            <div class="nav-enquiry" v-if="is_form_enquiry_and_book">
                <div class="enquiry-item active" >
                    <span>{{ __("Book") }}</span>
                </div>
                <div class="enquiry-item" data-toggle="modal" data-target="#enquiry_form_modal">
                    <span>{{ __("Enquiry") }}</span>
                </div>
            </div>
            <div class="form-book" :class="{'d-none':enquiry_type!='book'}">
                <div class="form-content">

                    <div class="form-group form-guest-search flex-wrap">
                        <div class="d-flex guest-wrapper">
                            <label>{{__("Return on same-day")}}</label>
                        </div>
                        <div class="guest-wrapper d-flex justify-content-between align-items-center">
                            <div class="flex-grow-1">
                                {{__("Hours")}}
                            </div>
                            <div class="flex-shrink-0">
                                <div class="input-number-group">
                                    <i class="icon ion-ios-remove-circle-outline" @click="minusHour()"></i>
                                    <span class="input"><input type="number" v-model="hour" min="0"/></span>
                                    <i class="icon ion-ios-add-circle-outline" @click="addHour()"></i>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex guest-wrapper">
                            <label>{{__("Return on another day")}}</label>
                        </div>
                        <div class="guest-wrapper d-flex justify-content-between align-items-center">
                            <div class="flex-grow-1">
                                {{__("Days")}}
                            </div>
                            <div class="flex-shrink-0">
                                <div class="input-number-group">
                                    <i class="icon ion-ios-remove-circle-outline" @click="minusDay()"></i>
                                    <span class="input"><input type="number" v-model="day" min="0"/></span>
                                    <i class="icon ion-ios-add-circle-outline" @click="addDay()"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group form-guest-search">
                        <div class="guest-wrapper d-flex justify-content-between align-items-center">
                            <div class="flex-grow-1">
                                <label>{{__("Start Time")}}</label>
                            </div>
                            <div class="flex-shrink-0">
                                <div class="input-number-group">
                                    <select v-model="start_time" class="form-control" @change="startTimeChange()">
                                        @php $startTime = strtotime( $row->start_time_booking ?? '00:00' );
                                             $endTime = strtotime( $row->end_time_booking ?? '23:30' );  @endphp
                                        @for ( $i = $startTime ; $i <= $endTime ; $i = $i + 1800)
                                            <option value="{{ date( 'H:i', $i ) }}">{{ date( 'H:i', $i ) }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group form-date-field form-date-search clearfix " data-format="{{get_moment_date_format()}}">
                        <div class="date-wrapper clearfix align-items-start" @click="openStartDate">
                            <div class="check-in-wrapper">
                                <label>{{__("Select Dates")}}</label>
                                <div class="render check-in-render" v-html="start_date_html"></div>
                                @if(!empty($row->min_day_before_booking))
                                    <div class="render check-in-render">
                                        <small>
                                            @if($row->min_day_before_booking > 1)
                                                - {{ __("Book :number days in advance",["number"=>$row->min_day_before_booking]) }}
                                            @else
                                                - {{ __("Book :number day in advance",["number"=>$row->min_day_before_booking]) }}
                                            @endif
                                        </small>
                                    </div>
                                @endif
                                <small class="alert-text mt10 mt-2" v-show="message2.content" v-html="message2.content" :class="{'danger':!message2.type,'success':message2.type}"></small>
                            </div>
                            <i class="fa fa-angle-down arrow"></i>
                        </div>
                        <input type="text" class="start_date" ref="start_date" style="height: 1px; visibility: hidden">
                    </div>
                    <div class="form-section-group form-group" v-if="extra_price.length">
                        <h4 class="form-section-title">{{__('Extra prices:')}}</h4>
                        <div class="form-group " v-for="(type,index) in extra_price">
                            <div class="extra-price-wrap d-flex justify-content-between">
                                <div class="flex-grow-1">
                                    <label><input type="checkbox" true-value="1" false-value="0" v-model="type.enable"> @{{type.name}}</label>
                                    <div class="render" v-if="type.price_type">(@{{type.price_type}})</div>
                                </div>
                                <div class="flex-shrink-0">@{{type.price_html}}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-section-group form-group-padding" v-if="buyer_fees.length">
                        <div class="extra-price-wrap d-flex justify-content-between" v-for="(type,index) in buyer_fees">
                            <div class="flex-grow-1">
                                <label>@{{type.type_name}}
                                    <i class="icofont-info-circle" v-if="type.desc" data-toggle="tooltip" data-placement="top" :title="type.type_desc"></i>
                                </label>
                                <div class="render" v-if="type.price_type">(@{{type.price_type}})</div>
                            </div>
                            <div class="flex-shrink-0">
                                <div class="unit" v-if='type.unit == "percent"'>
                                    @{{ type.price }}%
                                </div>
                                <div class="unit" v-else >
                                    @{{ formatMoney(type.price) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <ul class="form-section-total list-unstyled" v-if="total_price > 0">
                    <li>
                        <label>{{__("Total")}}</label>
                        <span class="price">@{{total_price_html}}</span>
                    </li>
                    <li v-if="is_deposit_ready">
                        <label for="">{{__("Pay now")}}</label>
                        <span class="price">@{{pay_now_price_html}}</span>
                    </li>
                </ul>
                <div v-html="html"></div>
                <div class="submit-group">
                    <a class="btn btn-large" @click="doSubmit($event)" :class="{'disabled':onSubmit,'btn-success':(step == 2),'btn-primary':step == 1}" name="submit">
                        <span v-if="step == 1">{{__("BOOK NOW")}}</span>
                        <span v-if="step == 2">{{__("Book Now")}}</span>
                        <i v-show="onSubmit" class="fa fa-spinner fa-spin"></i>
                    </a>
                    <div class="alert-text mt10" v-show="message.content" v-html="message.content" :class="{'danger':!message.type,'success':message.type}"></div>
                </div>
            </div>
            <div class="form-send-enquiry" v-show="enquiry_type=='enquiry'">
                <button class="btn btn-primary" data-toggle="modal" data-target="#enquiry_form_modal">
                    {{ __("Contact Now") }}
                </button>
            </div>
        </div>
    </div>
</div>
@include("Booking::frontend.global.enquiry-form",['service_type'=>'boat'])
