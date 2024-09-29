<div class="d-flex align-items-center">
    <div class="symbol symbol-20 symbol-lg-30 symbol-circle mr-3">
        @if (isset($r->tad->file->foto_filepath) && \Storage::exists($r->tad->file->foto_filepath))
            <img alt="Pic" src="{{ url('storage/' . $r->tad->file->foto_filepath) }}" />
        @else
            <span class="symbol-label font-size-h5">{{ $r->tad->nama[0] ?? '' }}</span>
        @endif
    </div>
    <div class="d-flex flex-column text-left">
        {{ $r->tad->nama }}<br>{{ $r->kepegawaian->unitKerja->name }}
    </div>
</div>
