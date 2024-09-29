<form action="{{ route($route . '.update', $record->id) }}" autocomplete="off" method="POST">
    @method('put')
    @csrf
    <div class="modal-header">
        <h4 class="modal-title">{{ $title }}</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i aria-hidden="true"
                class="ki ki-close"></i></button>
    </div>
    <div class="modal-body">
        <div class="form-group row">
            <label for="name" class="col-sm-3 col-form-label">@lang('Nama')</label>
            <div class="col-sm-9 parent-group">
                <input class="form-control" id="name" name="name" placeholder="@lang('Nama')"
                    value="{{ $record->name }}">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 col-form-label" for="nik">@lang('NIK')</label>
            <div class="col-sm-9 parent-group">
                <input id="nik" name="nik" class="form-control masking-nik" placeholder="@lang('NIK')"
                    value="{{ $record->nik }}">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 col-form-label" for="username">@lang('Username')</label>
            <div class="col-sm-9 parent-group">
                <input class="form-control" id="username" name="username" placeholder="@lang('Username')"
                    value="{{ $record->username }}">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 col-form-label" for="email">@lang('Email')</label>
            <div class="col-sm-9 parent-group">
                <input class="form-control" id="email" name="email" placeholder="@lang('Email')"
                    value="{{ $record->email }}">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 col-form-label" for="structCtrl">Unit Kerja</label>
            <div class="col-sm-9 parent-group">
                <select class="form-control base-plugin--select2 lokasi" data-size="7" data-live-search="true"
                    id="structCtrl" @if ($record->id == 1) disabled @endif name="org_struct_id"
                    title="Pilih Unit Kerja">
                    <option value=""></option>
                    @foreach ($struct as $level => $group)
                        @foreach ($group as $val)
                            @if ($loop->first)
                                <optgroup label="{{ \Modules\Master\Entities\SO\OrgStruct::getLevelLabel($level) }}">
                            @endif
                            <option
                                {{ isset($record->position->struct->id) && $record->position->struct->id == $val->id ? 'selected' : '' }}
                                data-positions='@json($val->positions)' value="{{ $val->id }}">
                                {{ $val->name }}
                            </option>
                            @if ($loop->last)
                                </optgroup>
                            @endif
                        @endforeach
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 col-form-label" for="">Jabatan</label>
            <div class="col-sm-9 parent-group">
                <select class="form-control base-plugin--select2" id="positionCtrl" name="position_id"
                    @if ($record->id == 1) disabled @endif>
                    <option value=""></option>
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 col-form-label" for="group">Hak Akses</label>
            <div class="col-sm-9 parent-group">
                @if ($record->id == 1)
                    <select name="groups" id="" class="form-control base-plugin--select2 show-tick" disabled>
                        <option value=""></option>
                        <option value="{{ $record->groups->first()->id ?? '' }}" selected>
                            {{ $record->groups->first()->name }}</option>
                    </select>
                @else
                    <select name="groups" id="" class="form-control base-plugin--select2 show-tick">
                        <option value=""></option>
                        @foreach (\App\Entities\Group::orderBy('name', 'asc')->get() as $item)
                            <option value="{{ $item->id }}"
                                {{ in_array($item->id, $record->groups->pluck('id')->toArray()) ? 'selected' : '' }}>
                                {{ $item->name }}
                            </option>
                        @endforeach
                    </select>
                @endif
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 col-form-label" for="group">Status</label>
            <div class="col-sm-9 parent-group">
                <select name="status" id="" class="form-control base-plugin--select2 show-tick"
                    data-placeholder="{{ __('Pilih Status') }}">
                    <option {{ $record->status === 'active' ? 'selected' : '' }} value="active">Aktif</option>
                    <option {{ $record->status === 'nonactive' ? 'selected' : '' }} value="nonactive">Non Aktif</option>
                </select>
            </div>
        </div>
        {{-- <hr>
        <div class="form-group row">
            <label class="col-sm-3 col-form-label">Password Baru</label>
            <div class="col-sm-9 parent-group">
                <input type="password" name="password" class="form-control" placeholder="Password Baru">
                <span class="form-text text-muted">Kosongkan jika tidak mengganti password</span>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 col-form-label">Konfirmasi Password Baru</label>
            <div class="col-sm-9 parent-group">
                <input type="password" name="password_confirmation" class="form-control"
                    placeholder="Konfirmasi Password Baru">
                <span class="form-text text-muted">Kosongkan jika tidak mengganti password</span>
            </div>
        </div> --}}
    </div>
    <div class="modal-footer border-0 pt-0">
        <x-btn-save via="base-form--submit-modal" confirm="0" />
    </div>
</form>

@if (isset($record->position->id))
    <script>
        $('#structCtrl').trigger('change');
        $('#positionCtrl').val('{{ $record->position->id }}').trigger('change');
    </script>
@endif

<script>
    $('.modal-dialog').removeClass('modal-md').addClass('modal-lg');
    $(".masking-nik").inputmask({
        "mask": "9",
        "repeat": 16,
        "greedy": false
    });
</script>
