<div>
  <span><span class="text-bold">Dari : </span>{{ $r->so->nama ?? '-' }}</span><br>
  <span><span class="text-bold">Pada Tgl : </span>{{ $r->created_at->format('d/m/Y') }}</span>
</div>
