@if ($ra->status == 'completed')
  @if ($r->read_at)
    <span>Sudah Di Baca {{ $r->read_at->format('d/m/Y H:i:s') }}</span>
  @else
    <span>Belum Dibaca</span>
  @endif
@else
  <span>-</span>
@endif
