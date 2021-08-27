<?php
$minValue = 0;
?>
<div class="form-select-seat-type">
	<div class="form-group">
		<i class="field-icon icofont-ticket"></i>
		<div class="form-content dropdown-toggle" data-toggle="dropdown">
			<div class="wrapper-more">
				<label> {{ $field['title'] }} </label>
				@php
					$seatTypeGet = request()->query('seat_type',[]);
				@endphp
				<div class="render font-size-14">
					@foreach($seatType as $type)
						<?php
						$inputRender = 'seat_type_'.$type->code.'_render';
						$inputValue = $seatTypeGet[$type->code] ?? $minValue;
						;?>
						<span class="" id="{{$inputRender}}">
                            <span class="one @if($inputValue > $minValue) d-none @endif">{{__( ':min :name',['min'=>$minValue,'name'=>$type->name])}}</span>
                            <span class="@if($inputValue <= $minValue) d-none @endif multi" data-html="{{__(':count '.$type->name)}}">{{__(':count'.$type->name,['count'=>$inputValue??$minValue])}}</span>
                        </span>
					@endforeach
				</div>
			</div>
		</div>
		<div class="dropdown-menu select-seat-type-dropdown" >
			@foreach($seatType as $type)
				<?php
				$inputName = 'seat_type_'.$type->code;
				$inputValue = $seatTypeGet[$type->code] ?? $minValue;
				;?>

				<div class="dropdown-item-row">
					<div class="label">{{__('Adults :type',['type'=>$type->name])}}</div>
					<div class="val">
						<span class="btn-minus" data-input="{{$inputName}}" data-input-attr="id"><i class="icon ion-md-remove"></i></span>
						<span class="count-display"><input id="{{$inputName}}" type="number" name="seat_type[{{$type->code}}]" value="{{$inputValue}}" min="{{$minValue}}"></span>
						<span class="btn-add" data-input="{{$inputName}}" data-input-attr="id"><i class="icon ion-ios-add"></i></span>
					</div>
				</div>
			@endforeach
		</div>
	</div>
</div>
