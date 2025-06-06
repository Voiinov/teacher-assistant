@props([
    "custom",
    "actionBtnId"=>"",
])

@isset($custom)
    {{ $slot }}
@else
    <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('Close') }}</button>
        <button type="button" id="{{$actionBtnId}}" class="btn btn-primary">{{ __('Save') }}</button>
    </div>
@endisset
    