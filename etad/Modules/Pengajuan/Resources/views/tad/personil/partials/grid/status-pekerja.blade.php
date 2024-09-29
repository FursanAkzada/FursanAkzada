@if (isset($r->kepegawaian))
    @if ($r->kepegawaian->status == \Modules\Master\Entities\Tad\Kepegawaian::RECRUITMENT && now()->format('Ymd') < $r->kepegawaian->in_at->format('Ymd'))
        {{-- <span class="badge badge-info">Penerimaan</span> --}}
	<span class="badge badge-primary">{{ $r->kepegawaian->unitKerja->name ?? '-' }}</span><br>
        <span class="badge badge-primary mt-1">{{ $r->jabatan->NM_UNIT ?? '-' }}</span><br>
    @elseif (
        $r->kepegawaian->status == \Modules\Master\Entities\Tad\Kepegawaian::MUTATION &&
            now()->format('Ymd') < $r->kepegawaian->in_at->format('Ymd'))
        <span class="badge badge-success">Proses Mutasi</span>
	{{-- <span class="badge badge-primary">{{ $r->kepegawaian->unitKerja->name ?? '-' }}</span><br>
        <span class="badge badge-primary mt-1">{{ $r->jabatan->NM_UNIT ?? '-' }}</span><br> --}}
    @elseif (
        $r->kepegawaian->status == \Modules\Master\Entities\Tad\Kepegawaian::RESIGN &&
            $r->kepegawaian->out_at &&
            now()->format('Ymd') < $r->kepegawaian->out_at->format('Ymd'))
        {{-- <span class="badge badge-warning">Proses Resign</span> --}}
	<?php
    	// Lakukan penanganan untuk mengubah status menjadi "work"
    	   $r->kepegawaian->status = \Modules\Master\Entities\Tad\Kepegawaian::WORK;
	   $r->kepegawaian->save();
    	?>
	<span class="badge badge-primary">{{ $r->kepegawaian->unitKerja->name ?? '-' }}</span><br>
        <span class="badge badge-primary mt-1">{{ $r->jabatan->NM_UNIT ?? '-' }}</span><br>
    @elseif ($r->kepegawaian->status == \Modules\Master\Entities\Tad\Kepegawaian::RESIGN)
        <span class="badge badge-danger">Resign</span>
    @else
        <div>
            <span class="badge badge-primary">{{ $r->kepegawaian->unitKerja->name ?? '-' }}</span><br>
            <span class="badge badge-primary mt-1">{{ $r->jabatan->NM_UNIT ?? '-' }}</span><br>
            {{-- @if (isset($r->kepegawaian->resign_at))
                <span class="badge badge-warning" title="Pengajuan Resign">({{ $r->kepegawaian->resign_at->format('d/m/Y') }})</span>
            @elseif(isset($r->kepegawaian->out_at))
                <span class="badge badge-danger" title="Resign Sejak">({{ $r->kepegawaian->out_at->format('d/m/Y') }})</span>
            @else
                <span class="badge badge-primary" title="Bekerja Sejak">({{ \Carbon\Carbon::createFromFormat('Y-m-d H:m:s', $r->kepegawaian->in_at)->format('d/m/Y') }})</span>
            @endif --}}
        </div>
    @endif
    {{-- 2: RESIGN --}}
@elseif(isset($r->lastEmployment->status) && $r->lastEmployment->status == 2)
    <span class="badge badge-danger">Resign</span>
@elseif(isset($r->kandidat->requirement) && $r->kandidat->requirement->flag == 'open')
    {{-- TODO: tambah kondisi if --}}
    <span class="badge badge-success">Seleksi</span>
@else
    <span class="badge badge-warning">Belum Bekerja</span>
@endif
