<form action="{{ route($route . '.cc.store', $record->id) }}" method="POST">
    @csrf
    <div class="modal-header">
        <h4 class="modal-title">{{ $title }}</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i aria-hidden="true"
                class="ki ki-close"></i></button>
    </div>
    <div class="modal-body">
        <div class="form-group row">
            <div class="col">
                <select name="user_id[]" id="" multiple class="form-control base-plugin--select2-ajax" title="Pilih User"
                    data-url="{{ route('settings.user.ajax.selectAjaxUserDivisiHC') }}">
                    <option value=""></option>
                    @foreach ($record->cc as $item)
                        <option value="{{ $item->id }}" selected>{{ $item->name }} ({{ $item->position_name }})
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="pt-0 border-0 modal-footer">
        <x-btn-save via="base-form--submit-page" />
    </div>
</form>
