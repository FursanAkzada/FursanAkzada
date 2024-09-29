<div>
    @if (isset($r->pengajuanMutasiPegawai))
        <span class="text-bold">Pengajuan Mutasi : </span><span>{{ $r->pengajuanMutasiPegawai->pengajuan->no_tiket }}</span><br>
        <span class="text-bold">Unit Kerja : </span><span>{{ $r->previousEmployment->unitKerja->name ?? '-' }}</span><br>
        {{-- <span class="text-bold">Posisi TAD : </span><span>{{ $r->tad->jabatan->NM_UNIT ?? '-' }}</span><br> --}}
        {{-- @if($r->resign_at)
            Pengajuan Resign ({{ date('d/m/Y', strtotime($r->previousEmployment->resign_at)) }})
        @elseif($r->out_at)
            Resign Sejak ({{ date('d/m/Y', strtotime($r->previousEmployment->out_at)) }})
        @else
            Bekerja Sejak ({{ \Carbon\Carbon::createFromFormat('Y-m-d H:m:s', $r->previousEmployment->in_at)->format('d/m/Y') }})
        @endif --}}
    @elseif(isset($r->kandidat->requirement->pengajuan->no_tiket))
        <span class="text-bold">Pengajuan TAD : </span><span>{{ $r->kandidat->requirement->pengajuan->no_tiket }}</span><br>
    @endif
</div>
