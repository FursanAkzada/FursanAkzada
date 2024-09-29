<div class="modal-header">
  <h4 class="modal-title">{{ $title }}</h4>
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i aria-hidden="true" class="ki ki-close"></i></button>
</div>
<div class="modal-body">
  <div class="form-group">
    <label for="name">{{ __('Name') }}</label>
    <input type="text" id="name" name="name" class="form-control" placeholder="Nama" value="{{ $record->name }}" readonly>
  </div>
</div>
