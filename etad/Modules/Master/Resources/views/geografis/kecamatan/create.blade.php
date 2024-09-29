<form action="{{ route($route.'.store') }}" method="POST">
  @csrf
  <div class="modal-header">
    <h4 class="modal-title">{{ $title }}</h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i aria-hidden="true" class="ki ki-close"></i></button>
  </div>
  <div class="modal-body">
    <div class="form-group">
      <label for="">Provinsi</label>
      <select name="parent_id" id="" class="form-control base-plugin--select2">
      </select>
    </div>
    <div class="form-group">
      <label for="">Kabupaten/Kota</label>
      <select name="parent_id" id="" class="form-control base-plugin--select2">
      </select>
    </div>
    <div class="form-group">
      <label for="">Kecamatan</label>
      <input type="text" class="form-control" name="code" placeholder="Kecamatan">
    </div>
  </div>
  <div class="modal-footer pt-0 border-0">
    <x-btn-save via="base-form--submit-modal" />
  </div>
</form>
