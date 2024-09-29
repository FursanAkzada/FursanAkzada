<form action="{{ route($route . '.update', $record->id) }}" method="POST">
    @csrf
    @method('put')
    <div class="modal-header">
        <h4 class="modal-title">{{ $title }}</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i aria-hidden="true"
                class="ki ki-close"></i></button>
    </div>
    <div class="form-group">
        <label for="">Induk</label>
        <select class="form-control base-plugin--select2" name="parent_id" title="Pilih Salah Satu">
            <option value="">-</option>
            @foreach (\Modules\Master\Entities\Penilaian\PertanyaanTad::kategori()->get() as $item)
                <option value="{{ $item->id }}" @if ($item->id == $record->parent_id) selected @endif>
                    {{ $item->pertanyaan }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label for="">Judul Pertanyaan</label>
        <input class="form-control" name="judul" type="text" value="{{ $record->judul }}"
            placeholder="Judul Pertanyaan">
    </div>
    <div class="form-group">
        <label for="">Deskripsi Pertanyaan</label>
        <textarea name="pertanyaan" id="" cols="30" rows="3" class="form-control"
            placeholder="Deskripsi Pertanyaan">{{ $record->pertanyaan }}</textarea>
    </div>
    <div class="pt-0 border-0 modal-footer">
        {{-- <x-btn-save via="base-form--submit-page" /> --}}
        <button type="submit" data-swal-confirm="false" data-rusmen="true"
            class="btn btn-info d-flex align-items-center base-form--submit-page">
            <i class="fas fa-save mr-2"></i>Simpan
        </button>
    </div>
</form>
