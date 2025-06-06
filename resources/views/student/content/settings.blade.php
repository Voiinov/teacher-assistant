{{ __("information.student.settings.intro") }}
<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <label>{{__("Expelled date")}}:</label>

            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
              </div>
              <input id="datemask" type="text" class="form-control" data-inputmask-alias="datetime" data-inputmask-inputformat="dd.mm.yyyy" data-mask="" inputmode="numeric" placeholder="dd.mm.yyyy">
              <div class="input-group-append">
                  <button type="button" class='btn btn-success'><i class="fa fa-check"></i></button>
              </div>
            </div>
            <span id="expelledDate-error" class="error invalid-feedback"></span>
            <span class="text-muted">{{ __("information.student.settings.expelled") }}</span>
            <!-- /.input group -->
          </div>
    </div>
</div>

@push("js")
<script>
    $('#datemask').inputmask('dd.mm.yyyy', { 'placeholder': 'dd.mm.yyyy' });
</script>
@endpush