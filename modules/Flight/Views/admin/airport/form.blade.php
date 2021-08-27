<div class="form-group">
    <label>{{__("Name")}}</label>
    <input type="text" value="{{$row->name??''}}" placeholder="{{__("Name")}}" name="name" class="form-control">
</div>
<div class="form-group">
    <label>{{__("Code")}}</label>
    <input type="text" value="{{$row->code??''}}" placeholder="{{__("Code")}}" name="code" class="form-control">
</div>

<div class="form-group">
    <label class="control-label">{{__("Description")}}</label>
    <div class="">
        <textarea name="content" class="d-none has-ckeditor" cols="30" rows="10">{{$row->content}}</textarea>
    </div>
</div>
<div class="form-group">
    <label>{{__("Location")}}</label>
    <select name="location_id" class="form-control">
        <option value="">{{__("-- Please Select --")}}</option>
        <?php
        $traverse = function ($array, $prefix = '') use (&$traverse, $row) {
            foreach ($array as $value) {
                if ($value->id == $row->id) {
                    continue;
                }
                $selected = '';
                if ($row->location_id == $value->id)
                    $selected = 'selected';
                printf("<option value='%s' %s>%s</option>", $value->id, $selected, $prefix . ' ' . $value->name);
                $traverse($value->children, $prefix . '-');
            }
        };
        $traverse($locations);
        ?>
    </select>
</div>
<div class="form-group">
    <label>{{__("Address")}}</label>
    <input type="text" value="{{$row->address??''}}" placeholder="{{__("Address")}}" name="address" class="form-control">
</div>
<div class="form-group form-index-hide">
    <label class="control-label">{{__("Location Map")}}</label>
    <p><i>{{__('Click onto map to place Location address')}}</i></p>
    <div class="control-map-group">
        <div id="map_content" class="{{!empty($map_full)?'mr-0 w-100':''}}"></div>
        <div class="g-control  {{!empty($map_full)?'position-static w-100 d-flex justify-content-between':''}}" >
            <div class="form-group">
                <label>{{__("Map Lat")}}:</label>
                <input type="text" name="map_lat" class="form-control" value="{{$row->map_lat}}">
            </div>
            <div class="form-group">
                <label>{{__("Map Lng")}}:</label>
                <input type="text" name="map_lng" class="form-control" value="{{$row->map_lng}}">
            </div>
            <div class="form-group">
                <label>{{__("Map Zoom")}}:</label>
                <input type="text" name="map_zoom" class="form-control" value="{{$row->map_zoom ?? "8"}}">
            </div>
        </div>
    </div>
</div>
