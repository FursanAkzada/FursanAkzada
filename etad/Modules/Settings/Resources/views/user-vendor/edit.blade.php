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
            <label class="col-sm-4 col-form-label" for="name">@lang('Nama')</label>
            <div class="col-sm-8 parent-group">
                <input id="name" name="name" value="{{ $record->name }}" class="form-control"
                    placeholder="@lang('Name')">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-4 col-form-label" for="username">@lang('Username')</label>
            <div class="col-sm-8 parent-group">
                <input class="form-control" disabled id="username" name="username" placeholder="@lang('Username')"
                    value="{{ $record->username }}">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-4 col-form-label" for="email">@lang('Email')</label>
            <div class="col-sm-8 parent-group">
                <input class="form-control" disabled id="email" name="email" value="{{ $record->email }}"
                    placeholder="@lang('Email')">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-4 col-form-label" for="vendor">@lang('Vendor')</label>
            <div class="col-sm-8 parent-group">
                <select name="vendor_id" class="form-control base-plugin--select2" id="">
                    <option value=""></option>
                    @foreach (\Modules\Master\Entities\Vendor::get() as $item)
                        <option value="{{ $item->id }}" {{ $record->vendor_id == $item->id ? 'selected' : '' }}>
                            {{ $item->nama }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        {{-- <div class="form-group">
      <label for="role">@lang('Jabatan')</label>
      <select name="roles[]" class="form-control base-plugin--select2 show-tick" multiple>
        @foreach ($roles as $role)
          <option value="{{ $role->name }}" {{ in_array($role->id,$record->roles()->pluck('id')->toArray()) ? 'selected' : '' }}>{{ $role->name }}</option>
        @endforeach
      </select>
    </div> --}}
        <div class="form-group row">
            <label class="col-sm-4 col-form-label" for="group">Hak Akses</label>
            <div class="col-sm-8 parent-group">
                @php
                    $temp_vendor = 0;
                @endphp
                <select class="form-control base-plugin--select2 show-tick" disabled>
                    @foreach (\App\Entities\Group::orderBy('name', 'asc')->get() as $item)
                        @if ($item->name == 'Vendor')
                            @php
                                $temp_vendor = $item->id;
                            @endphp
                            <option value="{{ $item->id }}" selected>{{ $item->name }}</option>
                        @endif
                    @endforeach
                </select>
                <input name="groups" id="" class="form-control" value="{{ $temp_vendor }}" type="hidden">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-4 col-form-label" for="group">Status</label>
            <div class="col-sm-8 parent-group">
                <select name="status" id="" class="form-control base-plugin--select2 show-tick"
                    data-placeholder="{{ __('Pilih Status') }}">
                    <option {{ $record->status === 'active' ? 'selected' : '' }} value="active">Aktif</option>
                    <option {{ $record->status === 'nonactive' ? 'selected' : '' }} value="nonactive">Non Aktif
                    </option>
                </select>
            </div>
        </div>
        {{-- <hr>
        <div class="form-group row">
            <label class="col-sm-4 col-form-label" for="password">@lang('Password')</label>
            <div class="col-sm-8 parent-group">
                <input type="password" id="password" name="password" class="form-control"
                    placeholder="@lang('Password')">
                <span class="form-text text-muted">Kosongkan jika tidak mengganti password</span>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-4 col-form-label" for="password">@lang('Konfirmasi Ulang Password Baru')</label>
            <div class="col-sm-8 parent-group">
                <input type="password" id="password" name="password_confirmation" class="form-control"
                    placeholder="@lang('Retype Password')">
                <span class="form-text text-muted">Kosongkan jika tidak mengganti password</span>
            </div>
        </div> --}}
    </div>
    <div class="modal-footer border-0 pt-0">
        <x-btn-save via="base-form--submit-modal" confirm="0" />
    </div>
</form>
