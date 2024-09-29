<form action="{{ route($route . '.store') }}" method="POST">
    @csrf
    <div class="modal-header">
        <h4 class="modal-title">{{ $title }}</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i aria-hidden="true"
                class="ki ki-close"></i></button>
    </div>
    <div class="modal-body">
        <div class="form-group">
            <label for="alasan">Alasan Resign</label>
            <input name="alasan" class="form-control" rows="4" placeholder="{{ __('Alasan Resign') }}" disabled value="{!! $record->alasan !!}">
        </div>
        <div class="form-group">
            <label for="description">Deskripsi</label>
            <textarea name="description" class="form-control" disabled rows="4" placeholder="{{ __('Deskripsi') }}">{{ $record->description }}</textarea>
        </div>
    </div>
</form>
