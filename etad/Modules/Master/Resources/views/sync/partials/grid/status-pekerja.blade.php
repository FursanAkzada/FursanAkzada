@if ($r->kepegawaian)
    <div>
        <span class="text-bold">Cabang :
        </span><span>{{ $r->kepegawaian->unitKerja->name ?? '' }}</span><br>
        <span class="text-bold">Jabatan : </span><span>{{ ($jab = $r->jabatan) ? $jab->NM_UNIT : '-' }}</span><br>
        {{-- @if (is_null($r->kepegawaian->out_at))
    <span class="text-success">Bekerja Sejak ({{ $r->kepegawaian->in_at->format('d/m/Y') }})</span>
  @else
    <span class="text-danger">Resign Sejak ({{ $r->kepegawaian->out_at->format('d/m/Y') }})</span>
  @endif --}}
    </div>
@else
    <span class="badge badge-warning">Belum Bekerja</span>
@endif
