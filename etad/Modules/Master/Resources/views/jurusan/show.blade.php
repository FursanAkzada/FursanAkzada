<form action="{{ route($route . '.show', $record->id) }}" method="POST">
    @csrf
    @method('PATCH')
    <div class="modal-header">
        <h4 class="modal-title">{{ $title }}</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i aria-hidden="true"
                class="ki ki-close"></i></button>
    </div>
    <div class="modal-body">
        <div class="form-group">
            <label for="">Pendidikan</label>
            <input disabled type="text" class="form-control" name="pendidikan_id" placeholder="Pendidikan"
                value="{{ $record->pendidikan->name }}">
        </div>
        <div class="form-group">
            <label for="">Nama</label>
            <input disabled type="text" class="form-control" name="name" placeholder="Nama"
                value="{{ $record->name }}">
        </div>
        {{-- <div class="form-group">
            <label for="">Deskripsi</label>
            <textarea disabled class="form-control" name="description" id="" cols="30" placeholder="Deskripsi">{{ $record->description }}</textarea>
        </div> --}}
    </div>
</form>
