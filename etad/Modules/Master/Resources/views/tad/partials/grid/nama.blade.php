@if (!$r->keluarga()->count())
<div class="d-flex align-items-center">
    <div class="symbol symbol-20 symbol-lg-30 symbol-circle mr-3">
        @if ($r->files->where('flag','foto3x4')->count() >0)
            @php
                $temp = $r->files->where('flag','foto3x4');
            @endphp
            <span class="cursor-pointer foto" data-path="{{ $temp->first()->file_path }}" data-toggle="modal" data-target="#exampleModal"><img class="pas-foto-kandidat-lg-30" alt="Pic"  src="{{ url('storage/'.$temp->first()->file_path) }}" /></span>
        @else
            <span class="symbol-label font-size-h5">{{ $r->nama[0] ?? '' }}</span>
        @endif
      </div>
      {{ $r->nama }}
      <i class="fas fa-exclamation-circle text-danger ml-2" data-toggle="tooltip" title="Data Keluarga Belum Ditambahkan"></i>
    </div>
@else
<div class="d-flex align-items-center">
  <div class="symbol symbol-20 symbol-lg-30 symbol-circle mr-3">
  @if ($r->files->where('flag','foto3x4')->count() >0)
      @php
          $temp = $r->files->where('flag','foto3x4');
      @endphp
      <span class="cursor-pointer foto" data-path="{{ $temp->first()->file_path }}" data-toggle="modal" data-target="#exampleModal"><img class="pas-foto-kandidat-lg-30" alt="Pic"  src="{{ url('storage/'.$temp->first()->file_path) }}" /></span>
  @else
      <span class="symbol-label font-size-h5">{{ $r->nama[0] ?? '' }}</span>
  @endif
  </div>
  {{ $r->nama }}
</div>
@endif
