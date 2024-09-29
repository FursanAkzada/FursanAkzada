
<style>
    .pas-foto-kandidat-lg-30 {
        border-radius: 50%;
        width: 30px;
        height: 30px;
    }
    ul {
        margin-left: -10px; /* Atur sesuai kebutuhan */
        padding-left: 0;
    }

    li {
        margin-left: 10px; /* Atur sesuai kebutuhan */
    }
</style>
@if (isset($r->summaryKandidat))
    @if($r->summaryKandidat->status == 'completed' && $r->summaryKandidat->details()->count() > 0)
        <div class="d-flex align-items-center" style="justify-content:center">
            @php 
                $overCount = 0;
                $overName = '';
            @endphp
            @foreach ($r->summaryKandidat->details as $i => $item)
                @if ($i < 3) 
                <div class="symbol symbol-20 symbol-lg-30 symbol-circle" data-toggle="tooltip" title="{{ $item->nama }}">
                    @if ($item->tad->files->where('flag','foto3x4')->count() >0)
                        @php
                                $temp = $item->tad->files->where('flag','foto3x4');
                            @endphp
                        <span class="cursor-pointer foto" data-path="{{ $temp->first()->file_path }}" data-toggle="modal" data-target="#exampleModal"><img class="pas-foto-kandidat-lg-30" alt="Pic"  src="{{ url('storage/'.$temp->first()->file_path) }}" /></span>
                    @else
                        <span class="symbol-label font-size-h5">{{ $item->tad->nama[0] ?? '' }}</span>
                    @endif
                </div>
                @else
                    @php 
                        $overCount++;
                        $overName .= "<li style='margin-left:-20px;'>" . $item->tad->nama . "</li>";
                    @endphp
                @endif
            @endforeach
            @if($overCount > 0)
                <div class="symbol symbol-20 symbol-lg-30 symbol-circle" data-toggle="tooltip" title="<ul>{!! $overName !!}</ul>" data-html="true" data-placement="right"><span class="symbol-label font-weight-bold">{!! $overCount !!}+</span>
                </div>
            @endif
        </div>
    @else
        <span class="font-italic badge text-white" style='background:brown;'>Kandidat Belum Ditambahkan</span>
    @endif
@else
    <span class="font-italic badge text-white" style='background:brown;'>Kandidat Belum Ditambahkan</span>
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

