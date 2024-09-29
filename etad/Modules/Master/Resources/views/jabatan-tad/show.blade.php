<form action="{{ route($route . '.show', $record->idunit) }}" method="POST">
    @csrf
    @method('PATCH')
    <div class="modal-header">
        <h4 class="modal-title">{{ $title }}</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i aria-hidden="true"
                class="ki ki-close"></i></button>
    </div>
    <div class="modal-body">
        <div class="form-group">
            <div class="form-group">
                <label for="">Kategori TAD</label>
                <select class="form-control base-plugin--select2 show-tick" name="kategori_id" disabled>
                    @foreach ($KATEGORI as $item)
                        <option value="{{ $item->id }}" {{ $item->id == $record->kategori_id ? 'selected' : '' }}>
                            {{ $item->nama }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="">Nama</label>
            <input class="form-control" name="deskripsi" disabled value="{{ $record->NM_UNIT }}">
        </div>
        <div class="form-group">
            <label for="">Jenis</label>
            <select class="form-control base-plugin--select2" disabled name="jenis" placeholder="Pilih Jenis">
                <option value="">Pilih Jenis</option>
                <option {{ $record->jenis === 'J-901' ?'selected':'' }} value="J-901">Administrasi</option>
                <option {{ $record->jenis === 'J-902' ?'selected':'' }} value="J-902">Non Administrasi</option>
            </select>
        </div>
    </div>
</form>
