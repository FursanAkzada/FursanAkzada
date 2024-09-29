<div class="d-flex align-items-center">
    <div class="symbol symbol-20 symbol-lg-30 symbol-circle mr-3">
        @if (isset($r->file->foto_filepath) && \Storage::exists($r->file->foto_filepath))
            <img alt="Pic" src="{{ url('storage/' . $r->file->foto_filepath) }}" />
        @else
            <span class="symbol-label font-size-h5">{{ $r->nama[0] ?? '' }}</span>
        @endif
    </div>
    <div class="d-flex flex-column text-left">
        <span class="">NIO: {{ $r->kepegawaian->nio }}</span>
        <span class="text-bold">{{ $r->nama }}</span>
    </div>
</div>
