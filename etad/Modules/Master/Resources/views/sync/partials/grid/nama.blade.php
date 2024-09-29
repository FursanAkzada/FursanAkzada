<div class="d-flex align-items-center">
    <div class="symbol symbol-20 symbol-lg-30 symbol-circle mr-3">
        @if (\Storage::exists($r->file->foto_filepath))
            <img alt="Pic" src="{{ url('storage/'.$r->file->foto_filepath) }}" />
        @else
            <span class="symbol-label font-size-h5">{{ $r->nama[0] }}</span>
        @endif
    </div>
    {{ $r->nama }}
</div>
