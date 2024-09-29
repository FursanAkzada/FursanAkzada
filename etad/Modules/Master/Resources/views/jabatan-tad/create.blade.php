<form action="{{ route($route . '.store') }}" method="POST">
    @csrf
    <div class="modal-header">
        <h4 class="modal-title">{{ $title }}</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i aria-hidden="true"
                class="ki ki-close"></i></button>
    </div>
    <div class="modal-body">
        <div class="form-group">
            <label for="">Kategori TAD</label>
            <select class="form-control base-plugin--select2" name="kategori_id" placeholder="Pilih Kategori">
                <option value="">Pilih Kategori TAD</option>
                @foreach ($KATEGORI as $item)
                    <option value="{{ $item->id }}">{{ $item->nama }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="">Jabatan</label>
            <input type="text" class="form-control" name="name" placeholder="Jabatan">
        </div>
        <div class="form-group">
            <label for="">Jenis</label>
            <select class="form-control base-plugin--select2" name="jenis" placeholder="Pilih Jenis">
                <option value="">Pilih Jenis</option>
                <option value="J-901">Administrasi</option>
                <option value="J-902">Non Administrasi</option>
            </select>
        </div>
    </div>
    <div class="modal-footer border-0 pt-0">
        {{-- <x-btn-save via="base-form--submit-page" /> --}}
        <button type="submit" data-swal-confirm="false" data-rusmen="true"
            class="btn btn-info d-flex align-items-center base-form--submit-page">
            <i class="fas fa-save mr-2"></i>Simpan
        </button>
    </div>
</form>
