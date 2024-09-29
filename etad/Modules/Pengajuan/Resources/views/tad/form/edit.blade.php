@php
    $org_struct_map = [
        'bod' => 'Direksi',
        'vice' => 'SEVP',
        'division' => 'Divisi',
        'departemen' => 'Sub Divisi',
        'cabang' => 'Cabang',
        'capem' => 'Cabang Pembantu',
        'kas' => 'Kantor Kas',
    ];
    $ORG_STRUCT = \Modules\Master\Entities\SO\OrgStruct::groupByLevel();
    // dd(22, json_decode($ORG_STRUCT['bod']));
    $user = auth()->user();
@endphp

@extends('layouts.app')
@section('title', $title)
@section('buttons-after') @endsection
@section('content')
    <style>
        .disabled.day {
            color: #7E8299 !important;
        }

        .day {
            color: #3F4254 !important;
        }
    </style>
    <form action="{{ route($route . '.update', $edit->id) }}" method="post">
        @method('put')
        @csrf
        <div class="card card-custom">
            <div class="card-header">
                <div class="card-toolbar">
                    <ul class="nav nav-light-danger nav-bold nav-pills">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#pengajuan">
                                <span class="nav-icon"><i class="fas fa-user-edit"></i></span>
                                <span class="nav-text">Pengajuan</span>
                            </a>
                        </li>
                        {{-- <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#tembusan">
                                <span class="nav-icon"><i class="fas fa-users"></i></span>
                                <span class="nav-text">Tembusan</span>
                            </a>
                        </li> --}}
                        {{-- <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#riwayat">
                                <span class="nav-icon"><i class="fas fa-code-branch"></i></span>
                                <span class="nav-text">Riwayat Pengajuan</span>
                            </a>
                        </li> --}}
                    </ul>
                    {{-- <div class="dropdown dropdown-inline">
                        <button type="button" class="btn btn-hover-light-primary btn-icon btn-sm" data-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
                            <i class="ki ki-bold-more-hor "></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item base-modal--render"
                                href="{{ route($route.'.cc.create', $edit->id) }}" data-toggle="tooltip"
                                data-original-title="Tambah Jadwal"><i class="fa fa-plus text-success mr-2"></i> Tambahkan
                                Tembusan</a>
                        </div>
                    </div> --}}
                </div>
                <button aria-label="Close" class="close" data-dismiss="card"
                    onclick="location.href='{{ route($route . '.index') }}'" type="button">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="card-body">
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="pengajuan" role="tabpanel" aria-labelledby="pengajuan">
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group row">
                                    <label for="" class="col-4 col-form-label col-form-label text-bold">Unit
                                        Kerja</label>
                                    <div class="col-8 parent-group">
                                        <input type="hidden" name="so_id" value="{{ $edit->so_id }}">
                                        <select class="form-control base-plugin--select2 show-tick filter-control"
                                            data-post="level" name="so_id" data-placeholder="Unit Kerja"
                                            id="tipeStrukturCtrl" disabled>
                                            <option value="">Unit Kerja</option>
                                            @if ($user->cekDivisiHC()){
                                                @foreach ($ORG_STRUCT as $level => $group)
                                                    @foreach ($group as $val)
                                                        @if ($loop->first)
                                                            <optgroup
                                                                label="{{ \Modules\Master\Entities\SO\OrgStruct::getLevelLabel($level) }}">
                                                        @endif
                                                        @if ($val->id == $edit->so_id)
                                                            <option data-level="{{ $level }}"
                                                                value="{{ $val->id }}" selected>{{ $val->name }}
                                                            </option>
                                                        @else
                                                            <option value="{{ $val->id }}">{{ $val->name }}</option>
                                                        @endif
                                                        @if ($loop->last)
                                                            </optgroup>
                                                        @endif
                                                    @endforeach
                                                @endforeach
                                            @else
                                                <option value="{{ $user->position->struct->id }}" selected>
                                                    {{ $user->position->struct->name }}
                                                </option>
                                            @endif
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group row">
                                    <label for="" class="col-4 col-form-label col-form-label text-bold">Pengajuan
                                        Untuk</label>
                                    <div class="col-4 parent-group">
                                        <input type="hidden" name="year" value="{{ $edit->year }}">
                                        <input class="form-control base-plugin--datepicker-3" id="yearCtrl" name="year"
                                            placeholder="Tahun" data-orientation="bottom" value="{{ $edit->year }}"
                                            disabled>
                                    </div>
                                    <div class="col-4 parent-group">
                                        <input type="hidden" name="semester" value="{{ $edit->semester }}">
                                        <select class="form-control base-plugin--select2 show-tick" id="semesterCtrl"
                                            name="semester" data-placeholder="Semester" disabled>
                                            <option value="">Pilih Semester</option>
                                            <option value="Satu" @if ($edit->semester == 'Satu') selected @endif>Satu
                                            </option>
                                            <option value="Dua" @if ($edit->semester == 'Dua') selected @endif>Dua
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group row">
                                    <label for="" class="col-4 col-form-label text-bold">Nomor Surat</label>
                                    <div class="col-8 parent-group">
                                        <input class="form-control" readonly value="{{ $edit->no_tiket }}"
                                            style="background-color: #F3F6F9">
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group row">
                                    <label for="" class="col-4 col-form-label text-bold">Tanggal Pengajuan</label>
                                    <div class="col-8">
                                        <input type="text" name="tgl_pengajuan"
                                            class="form-control base-plugin--datepicker" data-format="dd/mm/yyyy"
                                            data-options='@json(['endDate' => now()->format('d/m/Y'), 'format' => 'dd/mm/yyyy'])'
                                            placeholder="{{ __('Tgl Pengajuan') }}"
                                            value="{{ $edit->tgl_pengajuan->format('d/m/Y') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group row">
                                    <label for="" class="col-2 col-form-label text-bold">Perihal</label>
                                    <div class="col-10 parent-group">
                                        <input type="text" name="perihal" class="form-control"
                                            placeholder="{{ __('Perihal') }}" value="{{ $edit->perihal }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-2 col-form-label text-bold">Kepada</label>
                            <div class="col-10 parent-group">
                                <select name="to[]" id="" class="form-control base-plugin--select2-ajax"
                                    title="Jabatan" data-url="{{ route('settings.user.ajax.selectAjaxUserDivisiHC') }}">
                                    @foreach ($edit->to as $item)
                                        <option value="{{ $item->id }}" selected>{{ $item->name }}
                                          
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-2 col-form-label text-bold">Surat Permohonan</label>
                            <div class="col-10 parent-group">
                                <div class="custom-file">
                                    <input type="hidden" name="uploads_sp[uploaded]" class="uploaded" value="">
                                    <input type="file" multiple data-name="uploads_sp"
                                        class="custom-file-input base-form--save-temp-files" data-container="parent-group"
                                        data-max-size="2048" data-max-file="100" accept="*">
                                    <label class="custom-file-label" for="file"
                                        style="color:#B5B5C3;font-weight:400;">{{ 'Pilih file' }}</label>
                                </div>
                                <div class="form-text text-muted">*Maksimal 2MB</div>
                                @foreach ($edit->files->where('flag', 'surat_permohonan') as $file)
                                    <div class="progress-container w-100" data-uid="{{ $file->id }}">
                                        <div class="alert alert-custom alert-light fade show success-uploaded mb-0 mt-2 px-3 py-2"
                                            role="alert">
                                            <div class="alert-icon">
                                                <i class="{{ $file->file_icon }}"></i>
                                            </div>
                                            <div class="alert-text text-left">
                                                <input type="hidden" name="uploads_sp[files_ids][]"
                                                    value="{{ $file->id }}">
                                                <div>Uploaded File:</div>
                                                <a href="{{ $file->file_url }}" target="_blank" class="text-primary">
                                                    {{ $file->file_name }}
                                                </a>
                                            </div>
                                            <div class="alert-close">
                                                <button type="button" class="close base-form--remove-temp-files"
                                                    data-toggle="tooltip" data-original-title="Remove">
                                                    <span aria-hidden="true">
                                                        <i class="ki ki-close"></i>
                                                    </span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-2 col-form-label text-bold">SO (Struktur Organisasi)</label>
                            <div class="col-10 parent-group">
                                <div class="custom-file">
                                    <input type="hidden" name="uploads_so[uploaded]" class="uploaded" value="">
                                    <input type="file" multiple data-name="uploads_so"
                                        class="custom-file-input base-form--save-temp-files" data-container="parent-group"
                                        data-max-size="2048" data-max-file="100" accept="*">
                                    <label class="custom-file-label" for="file"
                                        style="color:#B5B5C3;font-weight:400;">{{ 'Pilih file' }}</label>
                                </div>
                                <div class="form-text text-muted">*Maksimal 2MB</div>
                                @foreach ($edit->files->where('flag', 'so') as $file)
                                    <div class="progress-container w-100" data-uid="{{ $file->id }}">
                                        <div class="alert alert-custom alert-light fade show success-uploaded mb-0 mt-2 px-3 py-2"
                                            role="alert">
                                            <div class="alert-icon">
                                                <i class="{{ $file->file_icon }}"></i>
                                            </div>
                                            <div class="alert-text text-left">
                                                <input type="hidden" name="uploads_so[files_ids][]"
                                                    value="{{ $file->id }}">
                                                <div>Uploaded File:</div>
                                                <a href="{{ $file->file_url }}" target="_blank" class="text-primary">
                                                    {{ $file->file_name }}
                                                </a>
                                            </div>
                                            <div class="alert-close">
                                                <button type="button" class="close base-form--remove-temp-files"
                                                    data-toggle="tooltip" data-original-title="Remove">
                                                    <span aria-hidden="true">
                                                        <i class="ki ki-close"></i>
                                                    </span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-2 col-form-label text-bold">Kalimat Pembuka</label>
                            <div class="col-10 parent-group">
                                <textarea name="pembukaan" class="base-plugin--summernote-2" data-height="200"
                                    placeholder="{{ __('Kalimat Pembuka') }}">{!! $edit->pembukaan !!}</textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-2 col-form-label text-bold">Kalimat Penutup</label>
                            <div class="col-10 parent-group">
                                <textarea name="penutupan" class="base-plugin--summernote-2" data-height="200"
                                    placeholder="{{ __('Kalimat Penutup') }}">{!! $edit->penutupan !!}</textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-2 text-bold">Tembusan</label>
                            <div class="col-10 parent-group">
                                <select name="cc[]" id="" multiple
                                    class="form-control base-plugin--select2-ajax" title="User HC"
                                    data-url="{{ route('settings.user.ajax.selectAjaxUserDivisiHC') }}">
                                    @foreach ($edit->cc as $item)
                                        <option value="{{ $item->id }}" selected>{{ $item->name }} ({{ $item->position->name }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <hr class="my-8">
                        <div id="requirement-row">
                            @foreach ($edit->requirement as $key => $requirement)
                                <div class="form-row justify-content-center" data-row="{{ $requirement->id }}">
                                    <div class="form-group col-3">
                                        <label for="">Kategori TAD</label>
                                        <select name="requirement[{{ $requirement->id }}][kategori_id]" field="kategori_id"
                                            row="{{ $requirement->id }}"
                                            onchange="select({{ $requirement->id }}, this, 'kategori')"
                                            class="kategori-ctrl form-control base-plugin--select2"
                                            data-placeholder="Kategori TAD">
                                            <option value=""></option>
                                            @foreach ($KATEGORI_VENDOR as $item)
						{{--@if ($item->id == 1)
                                                    @continue
                                                @endif--}}
                                                <option
                                                    {{ $requirement->jabatan->kategori_id == $item->id ? 'selected' : '' }}
                                                    value="{{ $item->id }}">{{ $item->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-3">
                                        <label for="">Posisi TAD</label>
                                        <select name="requirement[{{ $requirement->id }}][jabatan_id]" field="jabatan_id"
                                            row="{{ $requirement->id }}" onchange="select({{ $requirement->id }}, this)"
                                            class="jabatan-ctrl form-control base-plugin--select2"
                                            data-placeholder="Posisi TAD">
                                            <option value=""></option>
                                            @foreach (\App\Entities\EHC\Jabatan::where('kategori_id', $requirement->jabatan->kategori_id)-> orderBy('NM_UNIT', 'asc')->get() as $item)
                                                <option {{ $requirement->jabatan_id == $item->idunit ? 'selected' : '' }}
                                                    value="{{ $item->idunit }}">{{ $item->NM_UNIT }} {{-- (Kategori {{ $item->kategori_id }}) --}}</option>
                                            @endforeach 
					</select>
                                    </div>
                                    <div class="form-group col-1">
                                        <label for="">JK</label>
                                        <select name="requirement[{{ $requirement->id }}][jenis_kelamin]"
                                            field="jenis_kelamin" row="{{ $requirement->id }}"
                                            onchange="select({{ $requirement->id }}, this)" id=""
                                            class="form-control base-plugin--select2 gender-ctrl">
                                            <option value=""></option>
                                            <option {{ $requirement->jenis_kelamin == 'LP' ? 'selected' : '' }}
                                                value="LP">
                                                ALL
                                            </option>
                                            <option {{ $requirement->jenis_kelamin == 'L' ? 'selected' : '' }}
                                                value="L">
                                                L
                                            </option>
                                            <option {{ $requirement->jenis_kelamin == 'P' ? 'selected' : '' }}
                                                value="P">
                                                P
                                            </option>
                                        </select>
                                    </div>
                                    <div class="form-group col-1">
                                        <label for="">Jumlah</label>
                                        <input type="text" class="form-control masking-jumlah jumlah-ctrl"
                                            value="{{ $requirement->jumlah }}"
                                            name="requirement[{{ $requirement->id }}][jumlah]" field="jumlah"
                                            row="{{ $requirement->id }}" onchange="select({{ $requirement->id }}, this)"
                                            placeholder="Jumlah">
                                    </div>
                                    <div class="form-group col-3">
                                        <label for="">Vendor</label>
                                        <select name="requirement[{{ $requirement->id }}][vendor_id]" field="vendor_id"
                                            row="{{ $requirement->id }}" onchange="select({{ $requirement->id }}, this)"
                                            id="" class="form-control base-plugin--select2 vendor-ctrl"
                                            data-placeholder="Vendor">
                                            <option value=""></option>

                                            @foreach ($VENDOR as $item)
                                                <option {{ $requirement->vendor_id == $item->id ? 'selected' : '' }}
                                                    value="{{ $item->id }}">{{ $item->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-1">
                                        <label for="">&nbsp;</label>
                                        @if ($key == 0)
                                            <button class="btn btn-icon btn-primary btn-circle btn-add-requirement"
                                                onclick="addRow(this)" type="button">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        @else
                                            {{-- <a href="javascript:;"
                                                data-href="{{ route($route . '.requirement.delete', $requirement->id) }}"
                                                onclick="deleteRowExists(this)"
                                                class="btn btn-circle btn-icon btn-danger">
                                                <i class="fas fa-trash"></i>
                                            </a> --}}
					    <button type="button" onclick="deleteRowExists(this)" class="btn btn-circle btn-icon btn-danger" data-href="{{ route($route . '.requirement.delete', $requirement->id) }}">
    						<i class="fas fa-trash"></i>
					    </button>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    {{-- <div class="tab-pane fade" id="tembusan" role="tabpanel" aria-labelledby="tembusan">
                        @include('pengajuan::tad.form.tabs.tembusan')
                    </div> --}}
                </div>
            </div>
        </div>
        <br>
        <div class="row">
            @php
                $tipe = 'pengajuan.tad.form';
                switch ($edit->so->level) {
                    case 'bod':
                        $tipe = 'tad-bod';
                        break;
                    case 'vice':
                        $tipe = 'tad-vice';
                        break;
                    case 'division':
                        $tipe = 'tad-division';
                        break;
                    case 'departemen':
                        $tipe = 'tad-departemen';
                        break;
                    case 'cabang':
                        $tipe = 'tad-cabang';
                        break;
                    case 'capem':
                        $tipe = 'tad-capem';
                        break;
                    case 'kas':
                        $tipe = 'tad-kas';
                        break;
                }
            @endphp
            <div class="col-6">
                @include('pengajuan::tad.form.flow')
            </div>
            @if (auth()->user()->checkPerms($perms . '.edit'))
                <div class="col-6">
                    <div class="card card-custom" style="height:100%;">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between flex-lg-wrap flex-xl-nowrap p-4">
                                <div class="d-flex flex-column mr-5">
                                    <a href="#" class="h4 text-dark text-hover-primary mb-5">
                                        Informasi
                                    </a>
                                    <p class="text-dark-50">
                                        Sebelum submit pastikan data Pengajuan TAD tersebut sudah sesuai.
                                    </p>
                                </div>
                                <div class="ml-lg-0 ml-xxl-6 ml-6 flex-shrink-0">
                                    @php
                                        $menu = \Modules\Settings\Entities\Menu::where('code', $tipe)->first();
                                        $count = $menu->flows()->count();
                                        $submit = $count == 0 ? 'disabled' : 'enabled';
                                    @endphp
                                    <div style="display: none">
                                        <x-btn-back class="mr-2" url="{{ route($route . '.index') }}" />
                                    </div>
                                    <x-btn-draft via="base-form--submit-page" submit="{{ $submit }}" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </form>
    <template id="requirement-form">
        <div class="form-row justify-content-center" data-row="@{{ : index }}">
            <div class="form-group col-3">
                <select name="kategori_id[@{{ : index }}]" row="@{{ : index }}"
                    onchange="select(@{{ : index }}, this, 'kategori')"
                    class="kategori-ctrl form-control base-plugin--select2" data-placeholder="Pilih Kategori">
                    <option value=""></option>
                    @foreach ($KATEGORI_VENDOR as $item)
                        @if ($item->id == 1)
                            @continue
                        @endif
                        <option value="{{ $item->id }}">{{ $item->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-3">
                <select name="requirement[@{{ : index }}][jabatan_id]" field="jabatan_id"
                    row="@{{ : index }}" onchange="select(@{{ : index }}, this)" id=""
                    class="jabatan-ctrl form-control base-plugin--select2" data-placeholder="Pilih Posisi TAD">
                    <option value=""></option>
                </select>
            </div>
            <div class="form-group col-1">
                <select name="requirement[@{{ : index }}][jenis_kelamin]" field="jenis_kelamin"
                    row="@{{ : index }}" onchange="select(@{{ : index }}, this)" id=""
                    class="form-control base-plugin--select2 gender-ctrl" data-placeholder="JK">
                    <option value=""></option>
                    <option value="LP">ALL</option>
                    <option value="L">L</option>
                    <option value="P">P</option>
                </select>
            </div>
            <div class="form-group col-1">
                <input class="form-control masking-jumlah" name="requirement[@{{ : index }}][jumlah]"
                    field="jumlah" row="@{{ : index }}" onchange="select(@{{ : index }}, this)"
                    placeholder="Jumlah">
            </div>
            <div class="form-group col-3">
                <select name="requirement[@{{ : index }}][vendor_id]" field="vendor_id"
                    row="@{{ : index }}" onchange="select(@{{ : index }}, this)" id=""
                    class="form-control base-plugin--select2" data-placeholder="Pilih Vendor">
                    <option value=""></option>
                    @foreach ($VENDOR as $item)
                        <option value="{{ $item->id }}">{{ $item->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-1">
                <a href="javascript:;" onclick="deleteRow(this)" class="btn btn-icon btn-danger btn-circle"><i
                        class="fas fa-trash"></i></a>
            </div>
        </div>
    </template>
@endsection
@push('scripts')
    <script>
        const PERIODE_QUOTA = @json($QUOTA_PERIODE);
        addRow = (elem) => {
            // var row = $('#requirement-form').html();
            var index = $('.form-row:last').index();
            var resp = {
                'index': index + 1
            };
            var key = index + 1;
            var row = `
                <div class="form-row justify-content-center">
		    <div class="form-group col-3">
                        <select name="requirement[` + key + `][kategori_id]" row="` + key + `" field="kategori_id"
                            onchange="select(` + key + `, this, 'kategori')"
                            class="kategori-ctrl form-control base-plugin--select2" data-placeholder="Kategori TAD">
                            <option value=""></option>
                            @foreach ($KATEGORI_VENDOR as $item)
                                <option value="{{ $item->id }}">{{ $item->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-3">
                        <select name="requirement[` + key + `][jabatan_id]" row="` + key + `" field="jabatan_id"
                            onchange="select(` + key + `, this)" class="jabatan-ctrl form-control base-plugin--select2"
                            data-placeholder="Posisi TAD">
                            <option value=""></option>
                        </select>
                    </div>
                    <div class="form-group col-1">
                        <select name="requirement[` + key + `][jenis_kelamin]" row="` + key + `" field="jenis_kelamin"
                            onchange="select(` + key + `, this)" class="form-control base-plugin--select2 gender-ctrl"
                            data-placeholder="JK">
                            <option value=""></option>
                            <option value="LP">All</option>
                            <option value="L">L</option>
                            <option value="P">P</option>
                        </select>
                    </div>
                    <div class="form-group col-1">
                        <input type="text" class="form-control masking-jumlah jumlah-ctrl"
                            name="requirement[` + key + `][jumlah]" row="` + key + `" field="jumlah"
                            onchange="select(` + key + `, this)" placeholder="Jumlah">
                    </div>
                    <div class="form-group col-3">
                        <select name="requirement[` + key + `][vendor_id]" id="" row="` + key + `" field="vendor_id"
                            onchange="select(` + key + `, this)" class="form-control base-plugin--select2 vendor-ctrl"
                            data-placeholder="Vendor">
                            <option value=""></option>
			    @foreach ($VENDOR as $item)
                        	<option value="{{ $item->id }}">{{ $item->nama }}</option>
                            @endforeach
                        </select>
                    </div>
		    <div class="form-group col-1">
                        <a href="javascript:;" onclick="deleteRow(this)" class="btn btn-icon btn-danger btn-circle"><i
                                class="fas fa-trash"></i></a>
                    </div>
                </div>
			`;
            var tmpl = jsrender.templates(row);

            var dom = tmpl.render(resp);
            // console.log(163, dom);
            $('#requirement-row').append(dom);
            BasePlugin.initSelect2();
            $(".masking-jumlah").inputmask({
                "mask": "9",
                "repeat": 3,
                "greedy": false
            });
        }
        deleteRow = (elem) => {
            $(elem).parent().parent().remove();
        }
	
	deleteRowExists = (button) => {
		console.log('Button clicked!');
    		var dataHref = button.getAttribute('data-href');
    		
		fetch(dataHref, {
        		method: 'DELETE',
        		headers: {
            			'X-CSRF-TOKEN': '{{ csrf_token() }}',
            			'Content-Type': 'application/json',
            			// tambahkan header lain jika diperlukan
        		},
    		})
    		.then(response => response.json())
    		.then(data => {
        		console.log(data);
        		// lakukan tindakan lain jika diperlukan setelah penghapusan berhasil
        		$(button).parent().parent().remove();
    		})
    		.catch(error => console.error('Error:', error));
	}


        $(".masking-jumlah").inputmask({
            "mask": "9",
            "repeat": 3,
            "greedy": false
        });

	{{-- function deleteRowExists(element) {
    		var confirmation = confirm("Apakah Anda yakin ingin menghapus?");
    		if (confirmation) {
        		var url = $(element).data('href');
        
        		// Lakukan request DELETE ke URL
        		$.ajax({
            			url: url,
            			type: 'DELETE',
            			success: function(response) {
                			// Handle success, misalnya menghapus baris atau memuat ulang halaman
                			console.log(response);
                			// Contoh: hapus baris
                			$(element).closest('.form-row').remove();;
            			},
            			error: function(error) {
                			// Handle error
                			console.log(error);
            			}
        		});
    		}
	} --}}

        function select(row, input, ctrl) {
            kategori_id = $('select[name^="requirement"][row="' + row + '"][field="kategori_id"]').val();
            jabatanCtrl = $('select[name^="requirement"][row="' + row + '"][field="jabatan_id"]');
            jabatan_id = jabatanCtrl.val();
            vendorCtrl = $('select[name^="requirement"][row="' + row + '"][field="vendor_id"]');
            // vendor_id = vendorCtrl.val();
            vendor_id = $('select[name^="vendor_id"][row="' + row + '"]').val();
            jenis_kelamin = $('select[name^="requirement"][row="' + row + '"][field="jenis_kelamin"]').val();

            if (ctrl == 'kategori') {
                $.ajax({
                    method: 'GET',
                    url: '{{ url('master/jabatan-tad/ajax') }}',
                    data: {
                        kategori_id: kategori_id
                    },
                    success: function(response, state, xhr) {
			// console.log('AJAX Response: ', response);
                        // let options = `<option value='' selected disabled></option>`;
                        let options = ``;
                        for (let item of response) {
                            options += `<option value='${item.idunit}'>${item.NM_UNIT}</option>`;
                        }
                        {{-- $('#jabatanCtrl').select2('destroy');
                        $('#jabatanCtrl').html(options);
                        $('#jabatanCtrl').select2(); --}}
			jabatanCtrl.select2('destroy');
                        jabatanCtrl.html(options);
                        jabatanCtrl.select2();                  
		    },
                    error: function(a, b, c) {
                        console.log(a, b, c);
                    }
                });
                $.ajax({
                    method: 'GET',
                    url: '{{ url('master/vendor/ajax') }}',
                    data: {
                        kategori_id: kategori_id
                    },
                    success: function(response, state, xhr) {
                        // let options = `<option value='' selected disabled></option>`;
                        let options = ``;
                        for (let item of response) {
                            options += `<option value='${item.id}'>${item.nama}</option>`;
                        }
                        vendorCtrl.select2('destroy');
                        vendorCtrl.html(options);
                        vendorCtrl.select2();
                    },
                    error: function(a, b, c) {
                        console.log(a, b, c);
                    }
                }); 
            }

            var jabatanSelected_total = $('select:not([row="' + row + '"])[name^="jabatan_id"] option:selected[value="' +
                jabatan_id + '"]').length;
            var vendorSelected_total = $('select:not([row="' + row + '"])[name^="vendor_id"] option:selected[value="' +
                vendor_id + '"]').length;
            var jenisKelaminSelected_total = $('select:not([row="' + row +
                '"])[name^="jenis_kelamin"] option:selected[value="' + jenis_kelamin + '"]').length;

            var validateEmpty = kategori_id != '' && jabatan_id != '' && vendor_id != '' && jenis_kelamin != '' ? true :
                false;
            var validateTotal = jabatanSelected_total && jenisKelaminSelected_total && vendorSelected_total ? true : false;
            console.log(300, jabatanSelected_total, jenisKelaminSelected_total, vendorSelected_total);

            if (validateEmpty && validateTotal) {
                $.gritter.add({
                    title: 'Failed!',
                    text: 'Jabatan dan jenis kelamin di vendor tersebut sudah di pilih!',
                    image: baseurl + '/assets/images/icon/ui/cross.png',
                    sticky: false,
                    time: '3000'
                });
                $(input).val('').trigger('change');
            }
        }

        function periodeChanged(exists = false) {
            // if (exists) {
            //    $('select.kategori-ctrl').prop('disabled', false);
            //    $('select.jabatan-ctrl').prop('disabled', false);
            //    $('select.gender-ctrl').prop('disabled', false);
            //    $('.jumlah-ctrl').prop('disabled', false);
            //    $('select.vendor-ctrl').prop('disabled', false);
            //    $('.btn-add-requirement').prop('disabled', false);
            //    $('#submitBtn').removeClass('disabled');
            //    $('#submitBtn').prop('disabled', false);
            // }
        }

        $(document).ready(function() {
            periodeChanged();
            $(document)
                .on('change', '#tipeStrukturCtrl, #yearCtrl, #semesterCtrl', function() {
                    let year = $('#yearCtrl').val();
                    let semester = $('#semesterCtrl').val();
                    let level = $('#tipeStrukturCtrl option:selected').data('level');
                    $('#levelCtrl').val(level);
                    let periodeExists = false;
                    if (year != '' && semester != '' && year && semester) {
                        for (let periode of PERIODE_QUOTA) {
                            if (['approved', 'completed', 'new-struct', 'new-position'].includes(periode
                                    .status) && periode
                                .year == year && periode.semester == semester && periode.level == level) {
                                periodeExists = true;
                            }
                        }
                        if (periodeExists) {
                            periodeChanged(true);
                        } else {
                            periodeChanged();
                            $.gritter.add({
                                title: 'Failed!',
                                text: 'Periode Quota Tidak Tersedia.',
                                image: baseurl + '/assets/images/icon/ui/cross.png',
                                sticky: false,
                                time: '3000'
                            });
                            // $('#yearCtrl').val('').trigger('change');
                            $('#semesterCtrl').val('').trigger('change');
                        }
                    }
                });
        });

        $('#tipeStrukturCtrl').on('change', function() {
            let so_id = $('#tipeStrukturCtrl').val();
            $.ajax({
                method: 'GET',
                url: '{{ route('master.so.cek-level-tipe-struktur') }}',
                data: {
                    so_id: so_id,
                },
                success: function(response, state, xhr) {
                    console.log(720, response.level);
                    $('#flowContainer').html($('#' + response.level + 'Container').html());
                },
                error: function(a, b, c) {
                    console.log(a, b, c);
                }
            });
            // $("#tingkatTemuanCtrl").prop("disabled", false);
            // if(this.value == "non-finding"){
            //     // $('#tingkatTemuanCtrl').val('1'); // Select the option with a value of '1'
            //     $('#tingkatTemuanCtrl').val('');
            //     $('#tingkatTemuanCtrl').trigger('change');
            //     $("#tingkatTemuanCtrl").prop("disabled", true);
            // }else{
            //     $('#tingkatTemuanCtrl').val('Major'); // Select the option with a value of '1'
            //     $('#tingkatTemuanCtrl').trigger('change');
            // }
        });

        $('#tipeStrukturCtrl').trigger('change');
    </script>
@endpush
