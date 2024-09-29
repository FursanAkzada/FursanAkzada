@if (Storage::exists($tad->file->cv_filepath))
    <object data="{{ url('storage/' . $tad->file->cv_filepath) }}" type="application/pdf" width="100%"
        style="height: 85em;">
        <p>Alternatif link klik <a href="{{ url('storage/' . $tad->file->cv_filepath) }}"></a></p>
    </object>
@else
    <h4 class="text-center text-danger">
        File Tidak Tersedia (404)
    </h4>
@endif
