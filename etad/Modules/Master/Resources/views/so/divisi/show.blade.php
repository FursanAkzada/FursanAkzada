<div class="modal-header">
    <h4 class="modal-title">{{ $title }}</h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i aria-hidden="true"
            class="ki ki-close"></i></button>
</div>
<div class="modal-body">
    <div class="form-group">
        <label for="parent_id">Parent</label>
        <input type="text" id="parent_id" name="parent_id" class="form-control" placeholder="Parent"
            value="{{ $record->parent ? $record->parent->name : 'Tidak memiliki parent' }}" disabled>
        <span class="form-text text-muted">*Parent berupa Direksi</span>
    </div>
    <div class="form-group">
        <label >{{ __('Kode') }}</label>
        <input type="text" name="name" class="form-control" placeholder="Nama"
            value="{{ $record->code }}" disabled>
    </div>
    <div class="form-group">
        <label for="name">{{ __('Name') }}</label>
        <input type="text" id="name" name="name" class="form-control" placeholder="Nama"
            value="{{ $record->name }}" disabled>
    </div>
</div>
