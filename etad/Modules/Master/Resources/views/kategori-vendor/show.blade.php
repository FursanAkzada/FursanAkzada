<form action="{{ route($route.'.show', $record->id) }}" method="POST">
  @csrf
  @method('PATCH')
  <div class="modal-header">
    <h4 class="modal-title">{{ $title }}</h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i aria-hidden="true" class="ki ki-close"></i></button>
  </div>
  <div class="modal-body">
    <div class="form-group">
      <label for="">Nama</label>
      <input type="text" class="form-control" name="nama" placeholder="Nama" value="{{ $record->nama }}" disabled>
    </div>
    <div class="form-group">
      <label for="">Deskripsi</label>
      <textarea class="form-control" name="deskripsi" id="" cols="30" rows="5" disabled>{{ $record->deskripsi }}</textarea>
    </div>
  </div>
</form>
