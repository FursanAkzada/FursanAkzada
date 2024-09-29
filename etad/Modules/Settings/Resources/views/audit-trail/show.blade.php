{{-- {{ dd(json_decode($record), !is_null($record->new_data), $record->new_data) }} --}}
<div class="modal-header">
    <h4 class="modal-title">{{ $title }}</h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i aria-hidden="true"
            class="ki ki-close"></i></button>
</div>
<div class="modal-body">
    <div class="form-group row">
        <label for="" class="col-2 text-bold">User</label>
        <div class="col">
            <label for="">{{ $record->user->name }}</label>
        </div>
    </div>
    <div class="form-group row">
        <label for="" class="col-2 text-bold">Module</label>
        <div class="col">
            <label for="">{{ \App\Support\Base::getModules($record->module) }}</label>
        </div>
    </div>
    <div class="form-group row">
        <label for="" class="col-2 text-bold">Access</label>
        <div class="col">
            <label for="">{{ $record->ip_address }} {!! $record->actionRaw() !!}</label>
        </div>
    </div>
    <div class="form-group row">
        <label for="" class="col-2 text-bold">Browser</label>
        <div class="col">
            <label for="">{{ $record->browser }} {{ $record->created_at->diffForHumans() }}</label>
        </div>
    </div>
</div>
