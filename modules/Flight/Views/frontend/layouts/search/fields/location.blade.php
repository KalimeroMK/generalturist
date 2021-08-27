<?php
	if (empty($inputName)){
	    $inputName = 'location_id';
	}
?>
<div class="form-group">
	<i class="field-icon icofont-paper-plane"></i>
	<div class="form-content">
		<label>{{ $field['title'] ?? "" }}</label>
		<?php
		$location_name = "";
		$list_json = [];
		$traverse = function ($locations, $prefix = '') use (&$traverse, &$list_json, &$location_name,$inputName) {
			foreach ($locations as $location) {
				$translate = $location->translateOrOrigin(app()->getLocale());
				if (Request::query($inputName) == $location->id) {
					$location_name = $translate->name;
				}
				$list_json[] = [
						'id'    => $location->id,
						'title' => $prefix.' '.$translate->name,
				];
				$traverse($location->children, $prefix.'-');
			}
		};
		$traverse($list_location);
		?>
		<div class="smart-search">
			<input type="text" class="smart-search-location parent_text form-control font-size-14" {{ ( empty(setting_item("space_location_search_style")) or setting_item("space_location_search_style") == "normal" ) ? "readonly" : ""  }} placeholder="{{__("City or airport")}}" value="{{ $location_name }}" data-onLoad="{{__("Loading...")}}"
				   data-default="{{ json_encode($list_json) }}">
			<input type="hidden" class="child_id" name="{{$inputName}}" value="{{Request::query('location_id')}}">
		</div>
	</div>
</div>