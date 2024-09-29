<form action="{{ route($route.'.update', $record->idunit) }}" method="POST">
  @csrf
  @method('PATCH')
  <div class="modal-header">
    <h4 class="modal-title">{{ $title }}</h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i aria-hidden="true" class="ki ki-close"></i></button>
  </div>
  <div class="modal-body">
    <div class="form-group">
      <label for="NM_UNIT">Nama</label>
      <input type="text" class="form-control" name="NM_UNIT" id="NM_UNIT" placeholder="Nama" value="{{ $record->NM_UNIT }}">
    </div>
  </div>
  <div class="pt-0 border-0 modal-footer">
    <x-btn-save via="base-form--submit-page" />
  </div>
</form>