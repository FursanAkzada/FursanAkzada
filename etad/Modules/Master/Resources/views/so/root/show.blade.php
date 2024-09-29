<div class="modal-header">
    <h4 class="modal-title">{{ $title }}</h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i aria-hidden="true"
            class="ki ki-close"></i></button>
</div>
<div class="modal-body">
    <div class="form-group row">
        <label class="col-sm-4 col-form-label" for="name">{{ __('Name') }}</label>
        <div class="col-sm-8 parent-group">
            <input type="text" id="name" name="name" class="form-control" placeholder="Nama"
                value="{{ $record->name }}" disabled>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-4 col-form-label" for="name">{{ __('Email') }}</label>
        <div class="col-sm-8 parent-group">
            <input type="text" id="name" name="email" class="form-control" placeholder="Email"
                value="{{ $record->email }}" disabled>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-4 col-form-label" for="address">{{ __('Alamat') }}</label>
        <div class="col-sm-8 parent-group">
            <input type="text" id="address" name="address" class="form-control" placeholder="Alamat"
                value="{{ $record->address }}" disabled>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-4 col-form-label" for="province">Provinsi</label>
        {{-- <select name="province_id" class="form-control base-plugin--select2 show-tick" data-size="7" data-live-search="true" title="Pilih provinsi" id="provinsi">
	        <option value="">(Pilih Salah Satu)</option>
			@foreach ($province as $item)
			<option value="{{ $item->id }}" {{ $record->province_id == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
			@endforeach
		</select> --}}
        <div class="col-sm-8 parent-group">
            <input type="hidden" name="province_id" class="form-control" value="{{ $record->province_id }}">
            <input type="text" class="form-control" value="{{ $record->province->name ?? '' }}" disabled>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-4 col-form-label" for="city">Kota / Kabupaten</label>
        {{-- <select name="city_id" class="form-control base-plugin--select2 show-tick" data-size="7" data-live-search="true" title="Pilih kota / kabupaten" id="kabupaten">
	        <option value="">(Pilih Salah Satu)</option>
			@foreach ($city as $item)
			<option value="{{ $item->id }}" {{ $record->city_id == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
			@endforeach
		</select> --}}
        <div class="col-sm-8 parent-group">
            <input type="hidden" name="city_id" class="form-control" value="{{ $record->city->id ?? '' }}">
            <input type="text" class="form-control" value="{{ $record->city->name ?? '' }}" disabled>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-4 col-form-label" for="name">{{ __('Telepon') }}</label>
        <div class="col-sm-8 parent-group">
            <input type="text" id="name" name="phone" class="form-control" placeholder="Telepon"
                value="{{ $record->phone }}" disabled>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-4 col-form-label" for="name">{{ __('Fax') }}</label>
        <div class="col-sm-8 parent-group">
            <input type="text" id="name" name="fax" class="form-control" placeholder="Fax"
                value="{{ $record->fax }}" disabled>
        </div>
    </div>
</div>
