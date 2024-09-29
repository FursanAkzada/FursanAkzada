<form action="{{ route($route . '.store') }}" autocomplete="off" method="POST">
    @csrf
    <div class="modal-header">
        <h4 class="modal-title">{{ $title }}</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i aria-hidden="true"
                class="ki ki-close"></i></button>
    </div>
    <div class="modal-body">
        <div class="form-group row">
            <label class="col-sm-3 col-form-label" for="name">@lang('Nama')</label>
            <div class="col-sm-9 parent-group">
                <input type="text" id="name" name="name" class="form-control" placeholder="@lang('Nama')"
                    aria-autocomplete="none">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 col-form-label" for="nik">@lang('NIK')</label>
            <div class="col-sm-9 parent-group">
                <input type="text" id="nik" name="nik" class="form-control masking-nik"
                    placeholder="@lang('NIK')" aria-autocomplete="none">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 col-form-label" for="username">@lang('Username')</label>
            <div class="col-sm-9 parent-group">
                <input type="text" id="username" name="username" class="form-control"
                    placeholder="@lang('Username')" autocomplete="nameuser" aria-autocomplete="nameuser">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 col-form-label" for="email">@lang('Email')</label>
            <div class="col-sm-9 parent-group">
                <input type="text" id="email" name="email" class="form-control" placeholder="@lang('Email')"
                    aria-autocomplete="none">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 col-form-label" for="structCtrl">Unit Kerja</label>
            <div class="col-sm-9 parent-group">
                <select class="form-control base-plugin--select2 lokasi" data-size="7" data-live-search="true"
                    id="structCtrl" name="org_struct_id" title="Pilih Unit Kerja">
                    <option value=""></option>
                    @foreach ($struct as $level => $group)
                        @foreach ($group as $val)
                            @if ($loop->first)
                                <optgroup label="{{ \Modules\Master\Entities\SO\OrgStruct::getLevelLabel($level) }}">
                            @endif
                            <option value="{{ $val->id }}" data-positions='@json($val->positions)'>
                                {{ $val->name }}</option>
                            @if ($loop->last)
                                </optgroup>
                            @endif
                        @endforeach
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 col-form-label" for="positionCtrl">Jabatan</label>
            <div class="col-sm-9 parent-group">
                <select class="form-control base-plugin--select2" id="positionCtrl" name="position_id"
                    data-placeholder="Pilih Jabatan">
                    <option value=""></option>
                    {{-- @foreach (\Modules\Master\Entities\SO\Positions::orderBy('name', 'asc')->get() as $item)
                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                    @endforeach --}}
                </select>
            </div>
        </div>
        {{-- <div class="form-group">
            <label for="role">@lang('Hak Akses')</label>
            <select name="roles[]" class="form-control base-plugin--select2 show-tick" multiple>
                @foreach ($roles as $role)
                    <option value="{{ $role->name }}">{{ $role->name }}</option>
                @endforeach
            </select>
        </div> --}}
        <div class="form-group row">
            <label class="col-sm-3 col-form-label" for="group">Hak Akses</label>
            <div class="col-sm-9 parent-group">
                <select name="groups" id="" class="form-control base-plugin--select2 show-tick"
                    data-placeholder="{{ __('Pilih Hak Akses') }}">
                    <option value=""></option>
                    @foreach (\App\Entities\Group::orderBy('name', 'asc')->get() as $item)
                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 col-form-label" for="status">Status</label>
            <div class="col-sm-9 parent-group">
                <select name="status" id="" class="form-control base-plugin--select2 show-tick"
                    data-placeholder="{{ __('Pilih Status') }}">
                    <option value="active">Aktif</option>
                    <option value="nonactive">Non Aktif</option>
                </select>
            </div>
        </div>
        <hr>
        <div class="form-group row">
            <label class="col-sm-3 col-form-label" for="password">@lang('Password')</label>
            <div class="col-sm-9 parent-group toggle-password">
                <input aria-autocomplete="wordpass" autocomplete="wordpass" type="password" id="password"
                    name="password" class="form-control" placeholder="@lang('Password')">
                <div style="position: absolute; right: 20px; top: 12px; z-index:20;" tabindex="-1">
                    <a href="javascript:;" tabindex="-1"><i class="fa fa-eye-slash" aria-hidden="true"></i></a>
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 col-form-label" for="password">@lang('Konfirmasi Password')</label>
            <div class="col-sm-9 parent-group toggle-password">
                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control"
                    placeholder="@lang('Konfirmasi Password')">
                <div style="position: absolute; right: 20px; top: 12px; z-index:20;" tabindex="-1">
                    <a href="javascript:;" tabindex="-1"><i class="fa fa-eye-slash" aria-hidden="true"></i></a>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer border-0 pt-0">
        <x-btn-save via="base-form--submit-modal" confirm="0" />
    </div>
</form>

<script>
    $('.modal-dialog').removeClass('modal-md').addClass('modal-lg');
    $(".masking-nik").inputmask({
        "mask": "9",
        "repeat": 16,
        "greedy": false
    });
    $(".toggle-password a").on('click', function(event) {
        event.preventDefault();
        if ($(this).parent().parent().find('input').attr("type") == "text") {
            $(this).parent().parent().find('input').attr('type', 'password');
            $(this).parent().parent().find('i').addClass("fa-eye-slash");
            $(this).parent().parent().find('i').removeClass("fa-eye");
        } else if ($(this).parent().parent().find('input').attr("type") == "password") {
            $(this).parent().parent().find('input').attr('type', 'text');
            $(this).parent().parent().find('i').removeClass("fa-eye-slash");
            $(this).parent().parent().find('i').addClass("fa-eye");
        }
    });
</script>
