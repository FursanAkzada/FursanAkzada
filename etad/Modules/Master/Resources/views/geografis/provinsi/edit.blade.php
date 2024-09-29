<form action="{{ route($route . '.update', $record->id) }}" method="POST">
    @method('put')
    @csrf
    <div class="modal-header">
        <h4 class="modal-title">{{ $title }}</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i aria-hidden="true"
                class="ki ki-close"></i></button>
    </div>
    <div class="modal-body">
        <div class="form-group">
            <label for="">Nama Provinsi</label>
            <input type="text" class="form-control" name="name" placeholder="Provinsi" value="{{ $record->name }}">
        </div>
    </div>
    <div class="modal-footer pt-0 border-0">
        {{-- <x-btn-save via="base-form--submit-modal" /> --}}
        <button type="submit" data-swal-confirm="false" data-rusmen="true"
            class="btn btn-info d-flex align-items-center base-form--submit-modal">
            <i class="fas fa-save mr-2"></i>Simpan
        </button>
    </div>
</form>
