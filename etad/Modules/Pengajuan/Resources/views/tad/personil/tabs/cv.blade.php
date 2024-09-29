@if ($record->files->where('flag', 'cv')->count() > 0)
    @foreach($record->files->where('flag', 'cv') as $file)
    <div class="progress-container w-100" data-uid="{{ $file->id }}">
        <div class="alert alert-custom alert-light fade show py-2 px-3 mb-0 mt-2 success-uploaded" role="alert">
            <div class="alert-icon">
                <i class="{{ $file->file_icon }}"></i>
            </div>
            <div class="alert-text text-left">
                <input type="hidden" name="uploads_cv[files_ids][]" value="{{ $file->id }}">
                <div>Uploaded File:</div>
                <a href="{{ $file->file_url }}" target="_blank" class="text-primary">
                    {{ $file->file_name }}
                </a>
            </div>
        </div>
    </div>
    @endforeach
@else
    <h4 class="text-center text-danger">
        File Tidak Tersedia (404)
    </h4>
@endif
