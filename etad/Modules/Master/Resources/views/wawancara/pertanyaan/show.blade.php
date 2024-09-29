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
      <select class="form-control base-plugin--select2" name="kompetensi_id" title="Pilih Salah Satu" disabled>
        <option selected disabled></option>
        @foreach (\Modules\Master\Entities\Wawancara\Kompetensi::get() as $item)
        <option value="{{ $item->id }}" {{ $record->kompetensi_id == $item->id ? 'selected' : '' }}>{{ $item->kompetensi }}</option>
        @endforeach
      </select>
    </div>
    <div class="form-group">
      <label for="">Pertanyaan</label>
      <textarea class="form-control" name="pertanyaan" id="" cols="30" disabled>{{ $record->pertanyaan }}</textarea>
    </div>
  </div>
</form>
