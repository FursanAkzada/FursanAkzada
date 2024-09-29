<form action="{{ route($route . '.show', $record->sandi) }}" method="POST">
    @csrf
    @method('PATCH')
    <div class="modal-header">
        <h4 class="modal-title">{{ $title }}</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i aria-hidden="true"
                class="ki ki-close"></i></button>
    </div>
    <div class="modal-body">
        <div class="form-group row">
            <label class="col-4" for="">Nama</label>
            <div class="parent-group col-8">
                <input type="text" class="form-control" name="nama" placeholder="Nama" value="{{ $record->Lengkap }}"
                    disabled>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-4" for="">Deskripsi</label>
            <div class="parent-group col-8">
                <textarea class="form-control" name="description" disabled>{{ $record->description }}</textarea>
            </div>
        </div>
    </div>
</form>
