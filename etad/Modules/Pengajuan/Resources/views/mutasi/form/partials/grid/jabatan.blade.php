<div class="px-2">
    <span class="form-row mb-1">
        <i class="fas fa-sign-out-alt text-danger mr-2" data-toggle="tooltip" title="Jabatan Lama"></i>
        {{ $r->jabLama->NM_UNIT ?? '-' }}
    </span>
    <span class="form-row">
        <i class="fas fa-sign-in-alt text-success mr-2" data-toggle="tooltip" title="Jabatan Baru"></i>
        {{ $r->jabBaru->NM_UNIT ?? '-' }}
    </span>
</div>
