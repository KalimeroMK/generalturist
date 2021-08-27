<div class="modal fade" id="flightFormBookModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-body">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<div v-show="onLoading">
					<div class="icon-loading d-flex flex-horizontal-center flex-content-center">
						<i class="fa fa-spin icofont-spinner-alt-6"></i>
					</div>
				</div>
				<div class="card" v-show="!onLoading">
					<!-- Header -->
					<header class="card-header">
						<div class="row text-center">
							<div class="col-md-4">
								<div class="d-block d-lg-flex flex-horizontal-center">
									<img class="img-fluid mr-3 mb-3 mb-lg-0" :src="flight.airline.image_url??''" alt="Image-Description">
									<div class="font-size-14">@{{flight.title}} | @{{flight.code}}</div>
								</div>
							</div>
							<div class="col-md-3">
								<div class="flex-content-center align-items-start d-block d-lg-flex">
									<div class="mr-lg-3 mb-1 mb-lg-0">
										<i class="icofont-airplane font-size-30 text-primary"></i>
									</div>
									<div class="text-lg-left">
										<h6 class="font-weight-bold font-size-21 text-gray-5 mb-0" v-html="flight.departure_time_html"></h6>
										<div class="font-size-14 text-gray-5" v-html="flight.departure_date_html"></div>
										<span class="font-size-14 text-gray-1" v-html="flight.airport_from.name"></span>
									</div>
								</div>
							</div>
							<div class="col-md-2 flex-content-center flex-horizontal-center">
								<div class="flex-column">
									<h6 class="font-size-14 font-weight-bold text-gray-5 mb-0" v-html="flight.duration +' hrs'"></h6>
									<div class="width-60 border-top border-primary border-width-2 my-1"></div>
								</div>
							</div>
							<div class="col-md-3">
								<div class="mx-2 mx-xl-3 flex-content-center align-items-start d-block d-lg-flex">
									<div class="mr-lg-3 mb-1 mb-lg-0">
										<i class="d-block rotate-90 icofont-airplane-alt font-size-30 text-primary"></i>
									</div>
									<div class="text-lg-left">
										<h6 class="font-weight-bold font-size-21 text-gray-5 mb-0" v-html="flight.arrival_time_html"></h6>
										<div class="font-size-14 text-gray-5" v-html="flight.arrival_date_html"></div>
										<span class="font-size-14 text-gray-1" v-html="flight.airport_to.name"></span>
									</div>
								</div>
							</div>
						</div>
					</header>
					<div class="card-body py-4">
						<div class="row">
							<div class="col-12 mb-3">
								<ul class="d-block d-md-flex justify-content-between list-group list-group-borderless list-group-horizontal list-group-flush no-gutters border-bottom " v-for="(flight_seat,key) in flight.flight_seat" :key="key" v-if="flight_seat.max_passengers >0">
									<li class="mr-md-8 mr-lg-8 mb-3 mt-3 d-flex d-md-block justify-content-between list-group-item py-0 border-0">
										<div class="font-weight-bold text-dark">{{__('Seat type')}}</div>
										<span class="text-gray-1 text-capitalize" v-html="flight_seat.seat_type.name"></span>
									</li>
									<li class="mr-md-8 mr-lg-8 mb-3 mt-3 d-flex d-md-block justify-content-between list-group-item py-0 border-0">
										<div class="font-weight-bold text-dark">{{__('Baggage')}}</div>
										<span class="text-gray-1 text-capitalize" v-html="flight_seat.person"></span>
									</li>
									<li class="mr-md-8 mr-lg-8 mb-3 mt-3 d-flex d-md-block justify-content-between list-group-item py-0 border-0">
										<div class="font-weight-bold text-dark">{{__('Check-in')}}</div>
										<span class="text-gray-1" v-html="flight_seat.baggage_check_in+' Kgs'"></span>
									</li>
									<li class="mr-md-8 mr-lg-8 mb-3 mt-3 d-flex d-md-block justify-content-between list-group-item py-0 border-0">
										<div class="font-weight-bold text-dark">{{__('Cabin')}}</div>
										<span class="text-gray-1" v-html="flight_seat.baggage_cabin+' Kgs'"></span>
									</li>
									<li class="mr-md-8 mr-lg-8 mb-3 mt-3 d-flex d-md-block justify-content-between list-group-item py-0 border-0">
										<div class="font-weight-bold text-dark">{{__('Price')}}</div>
										<span class="text-gray-1" v-html="flight_seat.price_html"></span>
									</li>
									<li class="mr-md-8 mr-lg-8 mb-3 mt-3 d-flex d-md-block justify-content-between list-group-item py-0 border-0">
										<div class="font-weight-bold text-dark">{{__('Number')}}</div>
										<div class="flex-horizontal-center">
											<a class="font-size-10 text-dark" href="javascript:;" @click="minusNumberFlightSeat(flight_seat)">
												<i class="fa fa-chevron-down"></i>
											</a>
											<input class="form-control h-auto width-30  d-inline-block font-weight-bold  shadow-none border-0 rounded p-0 mx-1 text-center"  type="text" @change="updateNumberFlightSeat(flight_seat)"  v-model="flight_seat.number" min="1">
											<a class="font-size-10 text-dark" href="javascript:;" @click="addNumberFlightSeat(flight_seat)">
												<i class="fa fa-chevron-up"></i>
											</a>
										</div>
									</li>
								</ul>
							</div>
							<div class="col-12  col-lg-6 offset-lg-3">
								<div class="alert-text mt-3 text-left" v-show="message.content" v-html="message.content" :class="{'danger':!message.type,'success':message.type}"></div>
								<div class="min-width-250" v-show="total_price">
									<ul class="list-unstyled font-size-1 mb-0 font-size-16">
										<li class="d-flex justify-content-between py-2">
											<span class="font-weight-medium">{{__('Pay Amount')}}</span>
											<span class="font-weight-medium" v-html="total_price_html"></span>
										</li>
										<li class="d-flex justify-content-center py-2 font-size-17 font-weight-bold">
											<a @click="flightCheckOut()" class="btn btn-primary text-white">
												{{__('Book Now')}}
												<i v-show="onSubmit" class="fa fa-spinner fa-spin"></i>
											</a>
										</li>
									</ul>
								</div>
							</div>
						</div>
					</div>
					<!-- End Body -->
				</div>
			</div>
		</div>
	</div>
</div>