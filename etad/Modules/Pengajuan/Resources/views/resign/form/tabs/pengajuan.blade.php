@inject('carbon', '\Carbon\Carbon')

<div class="row">
    <div class="col-6">
        <div class="form-group row">
            <label for="" class="col-4 col-form-label text-bold">Unit Kerja</label>
            <div class="col-8 parent-group">
                <select class="form-control base-plugin--select2" id="unitKerjaCtrl" name="unit_kerja_id"
                    title="Pilih Unit Kerja" disabled>
                    <option value="{{ $record->so->id }}" @if ($record->unit_kerja_id) selected @endif>
                        {{ $record->so->name }}</option>
                </select>
            </div>
        </div>
    </div>
    <div class="col-6">
        <div class="form-group row">
            <label for="" class="col-4 col-form-label text-bold">Nomor Surat</label>
            <div class="col-md-8 parent-group">
                <input class="form-control" value="{{ $record->no_tiket }}" disabled>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-6">
        <div class="form-group row">
            <label for="" class="col-4 col-form-label text-bold">Tgl Pengajuan</label>
            <div class="col-8">
                <input type="text" name="tgl_pengajuan" class="form-control base-plugin--datepicker"
                    data-format="dd/mm/yyyy" data-options='@json(['endDate' => '', 'format' => 'dd/mm/yyyy'])'
                    placeholder="{{ __('Tgl Pengajuan') }}" @if ($record->tgl_pengajuan)
                value="{{ $record->tgl_pengajuan->format('d/m/Y') }}"
                @endif disabled>
            </div>
        </div>
    </div>
    <div class="col-6">
        <div class="form-group row">
            <label for="" class="col-4 col-form-label text-bold">Perihal</label>
            <div class="col-8 parent-group">
                <input type="text" name="perihal" class="form-control" disabled value="{{ $record->perihal }}">
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="form-group row">
            <label for="" class="col-2 col-form-label text-bold">Kepada</label>
            <div class="col-10 parent-group">
                <select name="to[]" disabled class="form-control base-plugin--select2-ajax" title="Pilih Kepada"
                    data-url="{{ route('settings.user.ajax.selectAjaxUserDivisiHC') }}">
                    @foreach ($record->to as $to)
                        <option selected value="{{ $to->id }}">
                            {{ $to->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
</div>
<div class="form-group row">
    <label for="" class="col-2 col-form-label text-bold">Kalimat Pembuka</label>
    <div class="col-10 parent-group">
        <textarea name="pembukaan" class="base-plugin--summernote-2" data-height="200"
            placeholder="{{ __('Kalimat Pembuka') }}" disabled>{!! $record->pembukaan !!}</textarea>
    </div>
</div>
<div class="form-group row">
    <label for="" class="col-2 col-form-label text-bold">Isi Surat</label>
    <div class="col-10 parent-group">
        <textarea name="isi_surat" class="form-control base-plugin--summernote-2" data-height="200"
            placeholder="{{ __('Isi Surat') }}" disabled>{!! $record->isi_surat !!}</textarea>
    </div>
</div>
<div class="form-group row">
    <label for="" class="col-2 col-form-label text-bold">Surat Permohonan</label>
    <div class="col-10 parent-group">
        @foreach ($record->files as $file)
            <div class="progress-container w-100" data-uid="{{ $file->id }}">
                <div class="alert alert-custom alert-light fade show success-uploaded mb-0 mt-2 px-3 py-2"
                    role="alert">
                    <div class="alert-icon">
                        <i class="{{ $file->file_icon }}"></i>
                    </div>
                    <div class="alert-text text-left">
                        <input type="hidden" name="uploads[files_ids][]" value="{{ $file->id }}">
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
    <label for="" class="col-2 col-form-label text-bold">Tembusan</label>
    <div class="col-10 parent-group">
        <select name="user_id[]" id="" multiple class="form-control base-plugin--select2-ajax"
            title="Pilih User" data-url="{{ route('settings.user.ajax') }}" disabled>
            <option value=""></option>
            @foreach ($record->cc as $item)
                <option value="{{ $item->id }}" selected>
                    {{ $item->name }}
                </option>
            @endforeach
        </select>
    </div>
</div>

@if (isset($tableStruct['datatable_1']))
<hr class="my-8">
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
{{-- <table class="table-bordered table">
    <thead>
        <tr>
            <th class="text-center">Nama</th>
            <th class="text-center">NIO</th>
            <th class="text-center">Vendor</th>
            <th class="text-center">Jabatan</th>
            <th class="text-center">Tgl. Resign</th>
            <th class="text-center">Tgl. Efektif</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($record->pegawai as $item)
            <tr>
                <td class="align-middle">
                    <div class="d-flex align-items-center">
                        <div class="symbol symbol-30 symbol-circle mr-2" data-toggle="tooltip"
                            title="{{ $item->nama }}">
                            @if (isset($item->file->foto_filepath) && \Storage::exists($item->file->foto_filepath))
                                <img alt="Pic" src="{{ url('storage/' . $item->file->foto_filepath) }}" />
                            @else
                                <span class="symbol-label font-size-h5">{{ $item->nama[0] }}</span>
                            @endif
                        </div>
                        {{ $item->nama }}
                    </div>
                </td>
                <td class="text-center">{{ $item->kepegawaian->nio ?? '-'}}</td>
                <td class="text-center">{{ $item->vendor->nama }}</td>
                <td class="text-center">{{ $item->jabatan->NM_UNIT }}</td>
                <td class="text-center">{{ $carbon->parse($item->pivot->tanggal_resign)->format('d/m/Y') }}</td>
                <td class="text-center">{{ $carbon->parse($item->pivot->tanggal_efektif)->format('d/m/Y') }}</td>
            </tr>
        @endforeach
    </tbody>
</table> --}}
