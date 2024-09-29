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
            <label for="">{{ ucwords(implode(' > ', explode('.', $record->module))) }}</label>
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
    {{-- @if ($record->action != 'access' || $record->action != 'read')
        @if (!is_null($record->old_data))
            <div class="form-group row">
                <label for="" class="col-2 text-bold">Old Data</label>
                <div class="col">
                    <pre>{{ dd($record->old_data) }}</pre>
                </div>
            </div>
        @endif
        @if (!is_null($record->new_data))
            <div class="form-group row">
                <label for="" class="col-2 text-bold">New Data</label>
                <div class="col">
                    <pre>{{ dd($record->new_data) }}</pre>
                </div>
            </div>
        @endif
    @endif --}}
</div>
