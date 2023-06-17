<div class="filter-item">
    <div class="form-group form-date-field form-date-search clearfix  has-icon is_single_picker">
        <i class="field-icon icofont-wall-clock"></i>
        <div class="date-wrapper clearfix">
            <div class="check-in-wrapper d-flex align-items-center">
                <div class="render check-in-render">{{Request::query('start',display_date(strtotime("today")))}}</div>
            </div>
        </div>
        <input type="hidden" class="check-in-input" value="{{Request::query('start',display_date(strtotime("today")))}}" name="start">
        <input type="text" class="check-in-out input-filter" name="date" value="{{Request::query('date')}}">
    </div>
</div>