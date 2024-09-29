<form action="{{ route($route .'.show', $record->id) }}" method="POST">
  @csrf
  @method('PATCH')
  <div class="modal-header">
    <h4 class="modal-title">{{ $title }}</h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i aria-hidden="true" class="ki ki-close"></i></button>
  </div>
  <div class="modal-body">
    <div class="form-group">
      <label for="">Induk</label>
      <select class="form-control base-plugin--select2" name="parent_id" title="Pilih Salah Satu" disabled>
        <option value="">-</option>
        @foreach (\Modules\Master\Entities\Pertanyaan\Tad::whereNull('parent_id')->get() as $item)
        <option value="{{ $item->id }}" @if($item->id == $record->parent_id) selected @endif>{{ $item->pertanyaan }}</option>
        @endforeach
      </select>
    </div>
    <div class="form-group">
      <label for="">Pertanyaan</label>
      <input class="form-control" type="text" value="{{ $record->pertanyaan }}" disabled>
    </div>
  </div>
</form>
