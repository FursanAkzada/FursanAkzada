<form action="{{ route($route . '.store') }}" method="POST">
    @csrf
    <input name="type" type="hidden" value="reward">
    <div class="modal-header">
        <h4 class="modal-title">{{ $title }}</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i aria-hidden="true"
                class="ki ki-close"></i></button>
    </div>
    <div class="modal-body">
        <div class="form-group row">
            <label class="col-4" for="">Jenis Reward</label>
            <div class="parent-group col-8">
                <input type="text" class="form-control" name="Lengkap" placeholder="Jenis Reward">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-4" for="">Deskripsi</label>
            <div class="parent-group col-8">
                <textarea class="form-control" name="description" placeholder="Deskripsi"></textarea>
            </div>
        </div>
    </div>
    <div class="modal-footer border-0 pt-0">
        {{-- <x-btn-save confirm="true" via="base-form--submit-page" /> --}}
        <button type="submit" data-swal-confirm="false" data-rusmen="true"
            class="btn btn-info d-flex align-items-center base-form--submit-page">
            <i class="fas fa-save mr-2"></i>Simpan
        </button>
    </div>
</form>
