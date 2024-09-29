@if ($show_title)
    <div class="modal-header">
        <h5 class="modal-title" id="rejectModalLabel">{!! $title !!}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <i aria-hidden="true" class="ki ki-close"></i>
        </button>
    </div>
@endif
<div class="mb-5">
    <div class="timeline timeline-6 ml-4 mt-3" style="max-height: 500px; overflow-y: auto;">
        @forelse ($record->logs_activity()->orderBy('created_at', 'DESC')->get() as $item)
            <div class="timeline-item align-items-start">
                <div class="timeline-label font-weight-bolder text-dark-75">
                    {{ $item->created_at->format('H:i:s') }}</div>
                <div class="timeline-badge">
                    <i class="fa fa-genderless text-info icon-md"></i>
                </div>
                <div class="font-weight-mormal timeline-content text-muted pl-3">
                    <span class="text-bold">{{ $item->message }}</span><br>
                    <span>Pada: {{ $item->created_at->format('d/m/Y H:i:s') }}</span><br>
                    <span>Oleh: {{ $item->creator->name }} ({{ $item->creator->position_name }}) </span>
                </div>
            </div>
        @empty
            <div class="timeline-item align-items-start mt-5 mb-5">
                <div class="timeline-label font-weight-bolder text-dark-75">
                </div>
                <div class="timeline-badge">
                    <i class="fa fa-genderless text-info icon-md"></i>
                </div>
                <div class="font-weight-mormal timeline-content text-muted pl-3">
                    <span class="text-bold">{{ __('Data tidak tersedia!') }}</span><br>
                </div>
        @endforelse
    </div>
</div>
