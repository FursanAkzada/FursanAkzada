<div class="d-flex align-items-center">
    <div class="symbol symbol-20 symbol-lg-30 symbol-circle mr-3">
        @if (\Storage::exists($r->foto_filepath))
            <img alt="{{ $r->name }}" src="{{ url('storage/' . $r->foto_filepath) }}" />
        @else
            <span class="symbol-label font-size-h5">{{ $r->name[0] }}</span>
        @endif
    </div>
    {{ $r->name }}
</div>
