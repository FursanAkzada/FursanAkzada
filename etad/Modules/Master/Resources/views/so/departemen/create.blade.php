<form action="{{ route($route . '.store') }}" method="POST">
    @csrf
    <div class="modal-header">
        <h4 class="modal-title">{{ $title }}</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i aria-hidden="true"
                class="ki ki-close"></i></button>
    </div>
    <div class="modal-body">
        <div class="form-group">
            <label for="parent_id">Parent</label>
            <select name="parent_id" class="form-control base-plugin--select2 show-tick" data-size="7"
                data-live-search="true" title="Pilih parent">
                <option value=""></option>
                @foreach ($parents as $item)
                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                @endforeach
            </select>
            <span class="form-text text-muted">*Parent berupa Divisi/Direksi</span>
        </div>
        <div class="form-group">
            <label for="">Kode</label>
            <input type="text" class="form-control" name="code" value="" placeholder="Kode">
        </div>
        <div class="form-group">
            <label for="">Nama</label>
            <input type="text" class="form-control" name="name" value="" placeholder="Nama">
        </div>
    </div>
    <div class="modal-footer border-0 pt-0">
        {{-- <x-btn-save via="base-form--submit-modal" /> --}}
        <button type="submit" data-swal-confirm="false" data-rusmen="true"
            class="btn btn-info d-flex align-items-center base-form--submit-modal">
            <i class="fas fa-save mr-2"></i>Simpan
        </button>
    </div>
</form>
