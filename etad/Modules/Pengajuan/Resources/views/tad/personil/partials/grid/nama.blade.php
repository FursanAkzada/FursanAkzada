<style>
    .pas-foto-kandidat-lg-30 {
        border-radius: 50%;
        width: 100%;
        max-width: 40px;
        height: 40px;
    }
</style>

@if ($r->keluarga()->count())
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
        <i class="fas fa-exclamation-circle text-danger ml-2" data-toggle="tooltip"
            title="Data Keluarga Belum Ditambahkan"></i>    
    </div>
@endif
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <img id="imgEl" style="max-height: 500px; max-width: 100%">
            </div>
        </div>
    </div>
</div>

    <script>
        $(document).ready(function(){
            $(document).on('click', 'span.foto', function(){
                let url = '{{ url('storage') }}/'+$(this).data('path');
                $('#imgEl').attr('src', url)
                    .prop('src', url);
            })
        });
    </script>
