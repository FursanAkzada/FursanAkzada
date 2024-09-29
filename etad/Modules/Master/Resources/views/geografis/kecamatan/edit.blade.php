<form action="{{ route($route . '.update', $record->id) }}" method="POST">
    @csrf
    @method('put')
    <div class="modal-header">
        <h4 class="modal-title">{{ $title }}</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i aria-hidden="true"
                class="ki ki-close"></i></button>
    </div>
    <div class="modal-body">
        <div class="form-group">
            <label for="">@lang('Nama Jabatan')</label>
            <input type="text" class="form-control" name="name" value="{{ $record->name }}"
                placeholder="@lang('Nama Jabatan')">
        </div>
        <div class="form-group">
            <label for="">@lang('Kode Jabatan')</label>
            <input type="text" class="form-control" name="code" value="{{ $record->code }}"
                placeholder="@lang('Kode Jabatan')">
        </div>
        <div class="form-group">
            <label for="">@lang('Atasan Langsung')</label>
            <select name="parent_id" id="" class="form-control base-plugin--select2">
                <option value=""></option>
                <option {{ $record->parent_id == 0 ? 'selected' : '' }} value="0">@lang('Tidak Ada')</option>
                @foreach (\App\Entities\Role::where('unit_id', $record->unit_id)->orderBy('name', 'asc')->get()
    as $item)
                    <option {{ $record->parent_id == $item->id ? 'selected' : '' }} value="{{ $item->id }}">
                        {{ $item->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="modal-footer pt-0 border-0">
        <x-btn-save via="base-form--submit-modal" />
    </div>
</form>
