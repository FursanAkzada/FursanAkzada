<div class="modal-header">
    <h4 class="modal-title">{{ $title }}</h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i aria-hidden="true"
            class="ki ki-close"></i></button>
</div>
<div class="modal-body">
    <div class="form-group row">
        <label class="col-sm-4 col-form-label" for="name">@lang('Nama')</label>
        <div class="col-sm-8 parent-group">
            <input class="form-control" disabled placeholder="@lang('Name')" value="{{ $record->name }}">
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
            <input class="form-control" disabled value="{{ $record->email }}">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-4 col-form-label" for="vendor">@lang('Vendor')</label>
        <div class="col-sm-8 parent-group">
            <input class="form-control" disabled value="{{ $record->vendor->nama ?? '' }}">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-4 col-form-label" for="group">Hak Akses</label>
        <div class="col-sm-8 parent-group">
            <input class="form-control" disabled value="{{ $record->groups->first()->name }}">
        </div>
    </div>
</div>
