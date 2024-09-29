@extends('layouts.app')
@section('title', $title)
@section('buttons-after') @endsection
@section('content')
<form action="{{ route($route . '.update', $record->id) }}" method="POST">
    @csrf
    @method('PATCH')
    <div class="card card-custom">
        <div class="card-header">
            <h5 class="card-title">
                Detail Kandidat
            </h5>
            <button aria-label="Close" class="close" data-dismiss="card"
                onclick="location.href='{{ route($route . '.index') }}'" type="button">
                <i aria-hidden="true" class="ki ki-close"></i>
            </button>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label">{{ __('ID Pengajuan') }}</label>
                        <div class="col-md-8 parent-group">
                            <input class="form-control" type="text" placeholder="{{ __('ID Pengajuan') }}"
                                value="{{$record->pengajuan->no_tiket}}" disabled>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label">{{ __('Unit Kerja') }}</label>
                        <div class="col-md-8 parent-group">
                            <input class="form-control" type="text" placeholder="{{ __('Unit Kerja') }}"
                                value="{{ $record->pengajuan->so->name ?? '' }}" disabled>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label">{{ __('Posisi TAD') }}</label>
                        <div class="col-md-8 parent-group">
                            <input class="form-control" type="text" placeholder="{{ __('Posisi TAD') }}"
                                value="{{ $record->requirement->jabatan->NM_UNIT . ' ( ' . $record->requirement->jumlah . ' posisi ' . ')'}}" disabled>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label">{{ __('Tgl Pengajuan') }}</label>
                        <div class="col-md-8 parent-group">
                            <input class="form-control" type="text" placeholder="{{ __('Tgl Pengajuan') }}"
                                value="{{ $record->pengajuan->tgl_pengajuan->translatedFormat('d/m/Y') }}" disabled>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label">{{ __('Periode') }}</label>
                        <div class="col-md-8 parent-group">
                            <input class="form-control" type="text" placeholder="{{ __('Periode') }}"
                                value="{{ 'Tahun ' . $record->pengajuan->year . ' Semester ' . $record->pengajuan->semester }}" disabled>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label">{{ __('Vendor') }}</label>
                        <div class="col-md-8 parent-group">
                            <input class="form-control" type="text" placeholder="{{ __('Vendor') }}" value="{{$record->requirement->vendor->nama}}" disabled>
                        </div>
                    </div>
                </div>
            </div>
            <hr class="my-8">
            <div class="text-right mr-5">
                <a href="{{ route($route . '.detailCreate', $record->id) }}" class="btn btn-info base-modal--render"
                    data-modal-position="default" data-modal-size="modal-xl" data-modal-backdrop="false"
                    data-modal-v-middle="false">
                    <i class="fa fa-plus"></i> Data
                </a>
            </div>
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
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-6">
            @include('pengajuan::tad.kandidat.flow')
        </div>
        <div class="col-6">
            <div class="card card-custom gutter-b" style="margin-bottom:0; height:100%;">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between p-4 flex-lg-wrap flex-xl-nowrap">
                        <div class="d-flex flex-column mr-5">
                            <span class="h4 text-dark text-hover-primary mb-5">
                                Informasi
                            </span>
                            <p class="text-dark-50">
                                Sebelum submit pastikan data Kandidat tersebut sudah sesuai.
                            </p>
                        </div>
                        <div class="ml-6 ml-lg-0 ml-xxl-6 flex-shrink-0">
                            @php
                                $menu = \Modules\Settings\Entities\Menu::where('code', 'pengajuan.tad.kandidat')->first();
                                // dd($menu);
                                $count = $menu->flows()->count();
                                $submit = $count == 0 ? 'disabled' : 'enabled';
                            @endphp
                            <div style="display: none">
                                <x-btn-back class="mr-2" url="{{ route($route . '.index') }}" />
                            </div>
                            <input type="hidden" name="is_submit" value="1">

                            <x-btn-draft via="base-form--submit-page" confirm="true" submit="{{ $submit }}" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
