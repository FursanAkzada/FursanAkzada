<div class="modal-header">
    <h4 class="modal-title">{{ $title }}</h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i aria-hidden="true"
            class="ki ki-close"></i></button>
</div>
<div class="modal-body">
    <div class="form-group row">
        <label class="col-sm-4 col-form-label" for="name">@lang('Nama')</label>
        <div class="col-sm-8 parent-group">
            <input class="form-control" disabled placeholder="@lang('Nama')" value="{{ $record->name }}">
        </div>
    </div>
    <div class="form-group row">
            <label class="col-sm-4 col-form-label" for="nik">@lang('NIK')</label>
            <div class="col-sm-8 parent-group">
                <input class="form-control" disabled placeholder="@lang('NIK')" value="{{ $record->nik }}">
            </div>
        </div>
    <div class="form-group row">
        <label class="col-sm-4 col-form-label" for="username">@lang('Username')</label>
        <div class="col-sm-8 parent-group">
            <input class="form-control" disabled placeholder="@lang('Username')" value="{{ $record->username }}">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-4 col-form-label" for="email">@lang('Email')</label>
        <div class="col-sm-8 parent-group">
            <input class="form-control" disabled placeholder="@lang('Email')" value="{{ $record->email }}">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-4 col-form-label" for="">Unit Kerja</label>
        <div class="col-sm-8 parent-group">
            <input class="form-control" disabled value="{{ $record->org_name }}">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-4 col-form-label" for="">Jabatan</label>
        <div class="col-sm-8 parent-group">
            <input class="form-control" disabled value="{{ $record->position_name }}">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-4 col-form-label" for="group">Hak Akses</label>
        <div class="col-sm-8 parent-group">
            <input class="form-control" disabled value="{{ $record->groups->first()->name }}">
        </div>
    </div>
</div>
