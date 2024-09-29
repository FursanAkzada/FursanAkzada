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
            <input class="form-control" id="title" name="title" placeholder="Judul" value="{{ $wawancara->title }}" disabled>
        </div>
        <div class="form-group">
            <label for="berkas">Hasil Wawancara</label>
            <div class="col parent-group">
                @foreach ($record->files->where('flag', 'wawancara-vendor') as $file)
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
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

</form>
