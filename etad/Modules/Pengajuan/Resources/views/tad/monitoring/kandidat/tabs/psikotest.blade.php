@if (Storage::exists($tad->file->psikotest_filepath))
    <object data="{{ url('storage/'.$tad->file->psikotest_filepath) }}" type="application/pdf" width="100%"
        style="height: 85em;">
        <p>Alternatif link klik <a href="{{ url('storage/'.$tad->file->psikotest_filepath) }}"></a></p>
    </object>
@else
    <h4 class="text-center text-danger">
        File Tidak Tersedia (404)
    </h4>
@endif
