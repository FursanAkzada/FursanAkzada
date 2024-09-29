<form action="{{ route($route . '.store') }}" method="POST">
    @csrf
    <div class="modal-header">
        <h4 class="modal-title">{{ $title }}</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i aria-hidden="true"
                class="ki ki-close"></i></button>
    </div>
    <div class="modal-body">
        <div class="form-group row">
            <label class="col-3" for="">Nama</label>
            <div class="parent-group col-9">
                <input class="form-control" name="nama" placeholder="Nama">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-3" for="">Deskripsi</label>
            <div class="parent-group col-9">
                <textarea class="form-control" name="deskripsi" id="" cols="30" placeholder="Deskripsi"></textarea>
            </div>
        </div>
    </div>
    <div class="pt-0 border-0 modal-footer">
        {{-- <x-btn-save confirm="true" via="base-form--submit-page" /> --}}
        <button type="submit" data-swal-confirm="false" data-rusmen="true"
            class="btn btn-info d-flex align-items-center base-form--submit-page">
            <i class="fas fa-save mr-2"></i>Simpan
        </button>
    </div>
</form>
