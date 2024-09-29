<form action="{{ route($route . '.wawancara.store', $personil->id) }}" method="POST">
    @csrf
    <input type="hidden" name="tad_id" value="{{ $personil->id }}">
    <div class="modal-header">
        <h4 class="modal-title">Tambah Wawancara Vendor</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <i aria-hidden="true" class="ki ki-close"></i>
        </button>
    </div>
    <div class="modal-body">
        <div class="form-group">
            <label for="title">Judul</label>
            <input class="form-control" id="title" name="title" placeholder="Judul">
        </div>
        <div class="form-group">
            <label for="berkas">Hasil Wawancara</label>
            <div class="custom-file parent-group">
                <input type="hidden" name="uploads[uploaded]" class="uploaded" value="">
                <input type="file" multiple data-name="uploads" class="custom-file-input base-form--save-temp-files"
                    data-container="parent-group" data-max-size="2048" data-max-file="100" accept=".pdf">
                <label class="custom-file-label" for="file" style="color:#B5B5C3;font-weight:400;">{{ 'Pilih file' }}</label>
            </div>
            <div class="form-text text-muted">*Maksimal 2MB</div>
        </div>
    </div>
    <div class="pt-0 border-0 modal-footer">
        <button type="submit" data-swal-confirm="false" data-rusmen="true"
            class="btn btn-info d-flex align-items-center base-form--submit-modal">
            <i class="fas fa-save mr-2"></i>Simpan
        </button>
    </div>
</form>
