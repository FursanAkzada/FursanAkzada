<form action="{{ route($route.'.update',$record->id) }}" method="POST">
    @method('put')
    @csrf
    <input type="hidden" name="id" value="{{ $record->id }}">
    <div class="modal-header">
        <h4 class="modal-title">{{ $title }}</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i aria-hidden="true" class="ki ki-close"></i></button>
    </div>
    <div class="modal-body">
        <div class="form-group">
            <label for="parent_id">Parent</label>
            <select name="parent_id" class="form-control base-plugin--select2 show-tick" data-size="7" data-live-search="true" title="Pilih parent">
                @foreach ($parents as $item)
                <option value="{{ $item->id }}" {{ $record->parent_id == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                @endforeach
            </select>
            <span class="form-text text-muted">*Parent berupa Cabang</span>
        </div>
        <div class="form-group">
            <label for="">Nama Kantor Kas</label>
            <input type="text" class="form-control" name="name" value="{{ $record->name }}" placeholder="Nama">
        </div>
        <div class="form-group">
            <label for="">Alamat</label>
            <textarea name="address" id="" cols="30" rows="4" placeholder="Alamat" class="form-control">{{ $record->address }}</textarea>
        </div>
        <div class="form-group">
            <label for="">Telepon</label>
            <input type="text" class="form-control base-plugin--inputmask_int" name="phone" value="{{ $record->phone }}" placeholder="Telepon">
        </div>
    </div>
    <div class="modal-footer pt-0 border-0">
        <button type="submit" 
            class="btn btn-info base-form--submit-modal">
            <i class="fa fa-save mr-1"></i>
            {{ __('Simpan') }}
        </button>
    </div>
</form>
