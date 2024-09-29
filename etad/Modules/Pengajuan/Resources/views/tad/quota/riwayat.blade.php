@if ($show_title)
    <div class="modal-header">
        <h5 class="modal-title" id="rejectModalLabel">{!! $title !!}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <i aria-hidden="true" class="ki ki-close"></i>
        </button>
    </div>
@endif
<div class="timeline timeline-6 ml-4 mt-3" style="max-height: 500px; overflow-y: auto;">
    @foreach ($periode->logs as $item)
        {{-- {{ dd(json_decode($item), json_decode($item->creator)) }} --}}
        <div class="timeline-item align-items-start">
            <div class="timeline-label font-weight-bolder text-dark-75">
                {{ $item->created_at->format('H:i:s') }}</div>
            <div class="timeline-badge">
                <i class="fa fa-genderless {{ $item->classLogs() }} icon-xl"></i>
            </div>
            <div class="font-weight-mormal timeline-content text-muted pl-3">
                <span class="text-bold">{{ $item->keterangan }}</span><br>
                <span>Pada: {{ $item->created_at->format('d/m/Y H:i:s') }}</span><br>
                <span>Oleh: {{ $item->creator->name }} ({{ $item->creator->isEhc ? $item->creator->position_name : 'Vendor '.($item->creator->vendor->nama ?? '') }}) </span>
            </div>
        </div>
    @endforeach
</div>
