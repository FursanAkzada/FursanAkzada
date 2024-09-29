<form action="{{ route($route . '.cc.update', $record->id) }}" method="POST">
    @method('put')
    @csrf
    <input type="hidden" name="id" value="{{ $record->id }}">
    <input type="hidden" name="rkia_id" value="{{ $record->rkia_id }}">
    <div class="modal-header">
        <h4 class="modal-title">{{ $title }}</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i aria-hidden="true"
                class="ki ki-close"></i></button>
    </div>
    <div class="modal-body">
        <div class="form-group">
            <label>Tembusan</label>
            <select name="user_id" id="" class="form-control base-plugin--select2">
                <option value=""></option>
                @foreach (\app\Entities\User::get() as $item)
                    <option {{ $record->user_id == $item->id ? 'selected' : '' }} value="{{ $item->id }}">
                        {{ $item->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="pt-0 border-0 modal-footer">
        <x-btn-save via="base-form--submit-page" />
    </div>
</form>
