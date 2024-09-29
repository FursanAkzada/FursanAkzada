<form action="{{ route($route . '.update', $record->id) }}" method="POST">
    @method('put')
    @csrf
    <input type="hidden" name="id" value="{{ $record->id }}">
    <div class="modal-header">
        <h4 class="modal-title">{{ $title }}</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i aria-hidden="true"
                class="ki ki-close"></i></button>
    </div>
    <div class="modal-body">
        <div class="form-group">
            <label for="parent_id">Parent</label>
            <input type="text" class="form-control" name="parent_id" placeholder="Parent" value="{{ $record->parent->name }}" disabled>
            <span class="form-text text-muted">*Parent berupa Cabang</span>
        </div>
        <div class="form-group">
            <label for="">Kode Cabang Pembantu</label>
            <input type="text" class="form-control" name="code" placeholder="Kode" value="{{ $record->code }}" disabled>
        </div>
        <div class="form-group">
            <label for="">Nama Cabang Pembantu</label>
            <input type="text" class="form-control" name="name" value="{{ $record->name }}" placeholder="Nama" disabled>
        </div>
        <div class="form-group">
            <label for="">Alamat</label>
            <textarea name="address" id="" cols="30" rows="4" placeholder="Alamat"
                class="form-control" disabled>{{ $record->address }}</textarea>
        </div>
        <div class="form-group">
            <label for="">Telepon</label>
            <input type="text" class="form-control base-plugin--inputmask_int" name="phone"
                value="{{ $record->phone }}" placeholder="Telepon" disabled>
        </div>
    </div>
</form>
