<div class="row">
    <div class="col-md-6">
        <div class="form-group row">
            <label for="" class="col-4 text-bold">Unit Kerja</label>
            <div class="col-8">
                <input class="form-control" disabled value="{{ $record->so->name  }}">
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-4 text-bold">Pengajuan Untuk</label>
            <div class="col-8">
                <input class="form-control" value="{{ $record->year . ' / ' . $record->semester  }}"disabled>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group row">
            <label for="" class="col-4 text-bold">Tanggal Pengajuan</label>
            <div class="col-8">
                <input class="form-control" disabled value="{{ $record->tgl_pengajuan->translatedFormat('d/m/Y')  }}">
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-4 text-bold">Nomor Surat</label>
            <div class="col-8">
                <input class="form-control" disabled value="{{ $record->no_tiket }}">
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="form-group row">
            <label for="" class="col-2 col-form-label text-bold">Perihal</label>
            <div class="col-10 parent-group">
                <input type="text" name="perihal" class="form-control" disabled placeholder="{{ __('Perihal') }}"
                    value="{{ $record->perihal }}">
            </div>
        </div>
    </div>
</div>
<div class="form-group row">
    <label for="" class="col-2 col-form-label text-bold">Kepada</label>
    <div class="col-10 parent-group">
        <select name="to[]" id="" class="form-control base-plugin--select2-ajax"
        disabled title="Pilih Kepada" data-url="{{ route('settings.user.ajax.selectAjaxUserDivisiHC') }}">
            @foreach ($record->to as $item)
                <option value="{{ $item->id }}" selected>{{ $item->name }}</option>
            @endforeach
        </select>
    </div>
</div>
<div class="form-group row">
    <label for="" class="col-2 col-form-label text-bold">Kalimat Pembuka</label>
    <div class="col-10">
        <textarea name="pembukaan" class="base-plugin--summernote-2" data-height="200" placeholder="{{ __('Kalimat Pembuka') }}" disabled>{!! $record->pembukaan !!}</textarea>
    </div>
</div>
<div class="form-group row">
    <label for="" class="col-2 col-form-label text-bold">Kalimat Penutup</label>
    <div class="col-10">
        <textarea name="penutupan" class="base-plugin--summernote-2" data-height="200" placeholder="{{ __('Kalimat Penutup') }}" disabled>{!! $record->penutupan !!}</textarea>
    </div>
</div>
<div class="form-group row">
    <label for="" class="col-2 text-bold">Tembusan</label>
    <div class="col-10">
        <select name="cc[]" id="" multiple class="form-control base-plugin--select2-ajax"
        title="User HC" data-url="{{ route('settings.user.ajax.selectAjaxUserDivisiHC') }}" disabled>
        @foreach ($record->cc as $item)
        <option value="{{ $item->id }}" selected>{{ $item->name }} ({{ $item->position->name }})</option>
        @endforeach
        </select>
    </div>
</div>
<div class="form-group row">
    <label for="" class="col-md-2 text-bold">Surat Permohonan</label>
    <div class="col-md-10">
        @foreach ($record->files->where('flag', 'surat_permohonan') as $file)
        <div class="progress-container w-100" data-uid="{{ $file->id }}">
            <div class="alert alert-custom alert-light fade show py-2 px-3 mb-0 mt-2 success-uploaded" role="alert">
                <div class="alert-icon">
                    <i class="{{ $file->file_icon }}"></i>
                </div>
                <div class="alert-text text-left">
                    <input type="hidden" name="uploads_sp[files_ids][]" value="{{ $file->id }}">
                    <div>Uploaded File:</div>
                    <a href="{{ $file->file_url }}" target="_blank" class="text-primary">
                        {{ $file->file_name }}
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
<div class="form-group row">
    <label for="" class="col-md-2 text-bold">Struktur Organisasi</label>
    <div class="col-md-10">
        @foreach ($record->files->where('flag', 'so') as $file)
        <div class="progress-container w-100" data-uid="{{ $file->id }}">
            <div class="alert alert-custom alert-light fade show py-2 px-3 mb-0 mt-2 success-uploaded" role="alert">
                <div class="alert-icon">
                    <i class="{{ $file->file_icon }}"></i>
                </div>
                <div class="alert-text text-left">
                    <input type="hidden" name="uploads_sp[files_ids][]" value="{{ $file->id }}">
                    <div>Uploaded File:</div>
                    <a href="{{ $file->file_url }}" target="_blank" class="text-primary">
                        {{ $file->file_name }}
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
<hr class="my-8">
@if (isset($tableStruct['datatable_1']))
    <table id="datatable_1" class="table table-bordered table-hover is-datatable hide" style="width: 100%;"
        data-url="{{ isset($tableStruct['url']) ? $tableStruct['url'] : route($route . '.grid') }}"
        data-paging="{{ $paging ?? true }}" data-info="{{ $info ?? true }}">
        <thead>
            <tr>
                @foreach ($tableStruct['datatable_1'] as $struct)
                    <th class="text-center v-middle" data-columns-name="{{ $struct['name'] ?? '' }}"
                        data-columns-data="{{ $struct['data'] ?? '' }}"
                        data-columns-label="{{ $struct['label'] ?? '' }}"
                        data-columns-sortable="{{ $struct['sortable'] === true ? 'true' : 'false' }}"
                        data-columns-width="{{ $struct['width'] ?? '' }}"
                        data-columns-class-name="{{ $struct['className'] ?? '' }}"
                        style="{{ isset($struct['width']) ? 'width: ' . $struct['width'] . '; ' : '' }}">
                        {{ $struct['label'] }}
                    </th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @yield('tableBody')
        </tbody>
    </table>
@endif
