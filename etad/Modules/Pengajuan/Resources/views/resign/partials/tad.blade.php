@if ($r->pegawai()->count() > 0)
    <div class="symbol-group symbol-hover" style="justify-content:center">
        @foreach ($r->pegawai()->limit(5)->get() as $item)
            <div class="symbol symbol-30 symbol-circle" data-toggle="tooltip" title="{{ $item->nama }}">
                @if (isset($item->file->foto_filepath) && \Storage::exists($item->file->foto_filepath))
                    <img alt="Pic" src="{{ url('storage/' . $item->file->foto_filepath) }}" />
                @else
                    <span class="symbol-label font-size-h5">{{ $item->nama[0] ?? '' }}</span>
                @endif
            </div>
	    <div class="d-flex flex-column text-left">
        	<span class="text">{{ $item->nama }}</span>
    	    </div>
        @endforeach
        @if ($r->pegawai()->count() > 5)
            <div class="symbol symbol-30 symbol-circle symbol-light-primary">
                <span class="symbol-label font-weight-bold">{{ $r->pegawai()->count() - 5 }}+</span>
            </div>
        @endif
    </div>
@else
    <span class="font-italic">Kandidat Belum Ditambahkan</span>
@endif
