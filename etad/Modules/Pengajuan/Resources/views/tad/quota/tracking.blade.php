@if ($show_title)
    <div class="modal-header">
        <h5 class="modal-title" id="rejectModalLabel">{!! $title !!}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <i aria-hidden="true" class="ki ki-close"></i>
        </button>
    </div>
@endif
<div class="timeline timeline-2 ml-4 mr-4">
    <div class="timeline-bar"></div>
    @forelse($record->approval($module)->get() as $val)
        <div class="timeline-item d-flex align-items-start">
            <span class="timeline-badge bg-{{ $val->show_color }} mt-5px"></span>
            <div class="timeline-content d-flex align-items-start justify-content-between">
                <span class="mr-3">
                    {{ $val->group->name }} <span class="text-{{ $val->show_color }}">({{ $val->show_type }})</span>
                    @if ($val->status === 'approved' && $val->user)
                        <div class="text-muted font-italic">
                            <div>{{ __('Approved by:') }} {{ $val->user->name }}</div>
                            <div>{{ __('Approved at:') }} {{ $val->approved_at->translatedFormat('d M Y, H:i:s') }}</div>
                        </div>
                    @else
                        <div class="text-muted font-italic">
                            <div>{{ __('Created by:') }} {{ $val->creatorName() }}</div>
                            <div>{{ __('Created at:') }} {{ $val->creationDate() }}</div>
                        </div>
                    @endif
                </span>
                <span class="text-muted font-italic text-right">
                    {!! $val->labelStatus() !!}
                </span>
            </div>
        </div>
    @empty
        <div class="timeline-item d-flex align-items-start">
            <span class="timeline-badge bg-primary mt-5px"></span>
            <div class="timeline-content d-flex align-items-start justify-content-between">
                <span class="mr-3">
                    [System]
                    <span class="text-primary"></span>
                    <div class="text-muted font-italic">
                        <div>{{ __('Created by:') }} [System]</div>
                        <div>{{ __('Created at:') }} {{ $record->creationDate() }}</div>
                    </div>
                </span>
                <span class="text-muted font-italic text-right">
                    Completed
                </span>
            </div>
        </div>
        {{-- <div class="alert alert-custom alert-light-danger align-items-center mb-0">
            <div class="alert-text">{{ __('Data tidak tersedia!') }}</div>
        </div> --}}
    @endforelse
</div>
