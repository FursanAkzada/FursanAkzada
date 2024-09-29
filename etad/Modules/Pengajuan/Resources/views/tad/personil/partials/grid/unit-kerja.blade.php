<div style="text-align: justify;">
    <div class="d-flex">
        <div class="text-bold" style="min-width: 75px;">Unit Kerja</div>
        <div class="text-bold" style="min-width: 10px;"> :</div>
        <div>
            <span style="text-align:justify;">{{ $r->unitKerja->name ?? '-' }}</span>
        </div>
    </div>
    <div class="d-flex">
        <div class="text-bold" style="min-width: 75px;">Posisi TAD</div>
        <div class="text-bold" style="min-width: 10px;"> :</div>
        <div>
            <span style="text-align:justify;">{{ $r->tad->jabatan->NM_UNIT ?? '-' }}</span>
        </div>
    </div>
</div>