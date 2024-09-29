<form action="{{ route($route.'.show', $record->id) }}" method="POST">
  @csrf
  @method('PATCH')
  <div class="modal-header">
    <h4 class="modal-title">{{ $title }}</h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i aria-hidden="true" class="ki ki-close"></i></button>
  </div>
  <div class="modal-body">
    <div class="form-group">
      <label for="">Kompetensi</label>
      <input type="text" class="form-control" name="kompetensi" placeholder="Kompetensi" value="{{ $record->kompetensi }}" disabled>
    </div>
    <div class="form-group">
      <label for="">Uraian</label>
      <textarea class="form-control" name="uraian" id="" cols="30" placeholder="Uraian" disabled>{{ $record->uraian }}</textarea>
    </div>
  </div>
</form>
