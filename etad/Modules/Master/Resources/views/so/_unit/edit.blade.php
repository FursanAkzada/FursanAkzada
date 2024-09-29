<form action="{{ route($route.'.update',$record->id) }}" method="POST">
  @method('put')
  @csrf
  <div class="modal-header">
    <h4 class="modal-title">{{ $title }}</h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i aria-hidden="true" class="ki ki-close"></i></button>
  </div>
  <div class="modal-body">
    <div class="form-group">
      <label for="">Nama Deperatemen</label>
      <input type="text" class="form-control" name="name" value="{{ $record->name }}" placeholder="Nama Sub Divisi">
    </div>
    <div class="form-group">
      <label for="">Kode Surat</label>
      <input type="text" class="form-control" name="mailing" value="{{ $record->mailing }}" placeholder="Kode Surat">
    </div>
    <div class="form-group">
      <label for="">Deskripsi</label>
      <textarea name="description" id="" cols="30" placeholder="Deskripsi" rows="3" class="form-control">{{ $record->description }}</textarea>
    </div>
  </div>
  <div class="modal-footer pt-0 border-0">
        <button type="submit" 
            class="btn btn-info base-form--submit-modal">
            <i class="fa fa-save mr-1"></i>
            {{ __('Simpan') }}
        </button>
    </div>
</form>
