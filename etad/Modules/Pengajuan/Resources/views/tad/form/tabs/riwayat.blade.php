<div class="timeline timeline-6 mt-3">
    @foreach ($record->logs()->orderBy('id', 'desc')->get() as $item)
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
