<form action="{{ route($route . '.wawancara.update', $wawancara->id) }}" method="POST">
    @csrf
    @method('PUT')
    <input type="hidden" name="tad_id" value="{{ $wawancara->tad_id }}">
    <div class="modal-header">
        <h4 class="modal-title">Ubah Wawancara Vendor</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <i aria-hidden="true" class="ki ki-close"></i>
        </button>
    </div>
    <div class="modal-body">
        <div class="form-group">
            <label for="title">Judul</label>
            <input class="form-control" id="title" name="title" placeholder="Judul" value="{{ $wawancara->title }}">
        </div>
        <div class="form-group">
            <label for="berkas">Hasil Wawancara</label>
            <div class="parent-group">
                <div class="custom-file">
                    <input type="hidden" name="uploads_psikotest[uploaded]" class="uploaded" value="">
                    <input type="file" multiple data-name="uploads_psikotest" class="custom-file-input base-form--save-temp-files"
                        data-container="parent-group" data-max-size="2048" data-max-file="100" accept=".pdf">
                    <label class="custom-file-label" for="file" style="color:#B5B5C3;font-weight:400;">{{ 'Pilih file' }}</label>
                </div>
                <div class="form-text text-muted">*Maksimal 2MB</div>
                @foreach ($wawancara->files->where('flag', 'wawancara-vendor') as $file)
                <div class="progress-container w-100" data-uid="{{ $file->id }}">
                    <div class="alert alert-custom alert-light fade show py-2 px-3 mb-0 mt-2 success-uploaded" role="alert">
                        <div class="alert-icon">
                            <i class="{{ $file->file_icon }}"></i>
                        </div>
                        <div class="alert-text text-left">
                            <input type="hidden" name="uploads[files_ids][]" value="{{ $file->id }}">
                            <div>Uploaded File:</div>
                            <a href="{{ $file->file_url }}" target="_blank" class="text-primary">
                                {{ $file->file_name }}
                            </a>
                        </div>
                        <div class="alert-close">
                            <button type="button" class="close base-form--remove-temp-files" data-toggle="tooltip"
                                data-original-title="Remove">
                                <span aria-hidden="true">
                                    <i class="ki ki-close"></i>
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="pt-0 border-0 modal-footer">
        {{-- <x-btn-save via="base-form--submit-page" /> --}}
        <button type="submit" data-swal-confirm="false" data-rusmen="true"
            class="btn btn-info d-flex align-items-center base-form--submit-modal">
            <i class="fas fa-save mr-2"></i>Simpan
        </button>
    </div>
</form>
