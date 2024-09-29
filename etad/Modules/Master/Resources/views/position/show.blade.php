<form action="{{ route($route.'.show', $record->id) }}" method="POST">
  @csrf
  @method('PATCH')
  <div class="modal-header">
    <h4 class="modal-title">{{ $title }}</h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i aria-hidden="true" class="ki ki-close"></i></button>
  </div>
  <div class="modal-body">
    <div class="form-group">
      <label for="">Pertanyaan</label>
      <textarea class="form-control" name="pertanyaan" id="" cols="30" disabled>{{ $record->pertanyaan }}</textarea>
    </div>
  </div>
</form>
