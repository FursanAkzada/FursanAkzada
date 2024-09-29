<form action="{{ route($route . '.show', $record->id) }}" method="POST">
    @csrf
    @method('PATCH')
    <input type="hidden" name="id" value="{{ $record->id }}">
    <div class="modal-header">
        <h4 class="modal-title">{{ $title }}</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i aria-hidden="true"
                class="ki ki-close"></i></button>
    </div>
    <div class="modal-body">
        <div class="form-group row">
            <label class="col-sm-4 col-form-label" for="org_struct_id">Struktur</label>
            <div class="col-sm-8 parent-group">
                <select name="org_struct_id" class="form-control base-plugin--select2 lokasi" data-size="7"
                    data-live-search="true" title="Pilih Struktur" disabled>
                    <option value=""></option>
                    @foreach ($struct as $group)
                        @foreach ($group as $val)
                            @if ($loop->first)
                                <optgroup label="{{ $val->show_level }}">
                            @endif
                            <option value="{{ $val->id }}"
                                {{ $val->id == $record->org_struct_id ? 'selected' : '' }}>{{ $val->name }}</option>
                            @if ($loop->last)
                                </optgroup>
                            @endif
                        @endforeach
                    @endforeach
                </select>
            </div>
        </div>
        {{-- <div class="form-group">
            <label for="parent_id">Parent</label>
            <select name="parent_id" class="form-control base-plugin--select2 render-parent" data-size="7" data-live-search="true" title="Pilih parent">
                <option value=""></option>
                @foreach ($parent as $val)
					<option value="{{ $val->id }}" {{ $val->id == $record->parent_id ? 'selected' : '' }}>{{ $val->name }}</option>
				@endforeach
            </select>
        </div> --}}
        <div class="form-group row">
            <label class="col-sm-4 col-form-label" for="">Nama Jabatan</label>
            <div class="col-sm-8 parent-group">
                <input type="text" class="form-control" name="name" value="{{ $record->name }}"
                    placeholder="Nama Jabatan" disabled>
            </div>
        </div>
    </div>
</form>
<script>
    $('#modal .modal-md')
        .removeClass('modal-md')
        .addClass('modal-lg');
</script>
