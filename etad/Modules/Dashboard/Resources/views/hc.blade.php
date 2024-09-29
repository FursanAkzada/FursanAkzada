@extends('layouts.app')
@section('title', $title)
@push('styles')
    <style>
        .chart-finding-wrapper .apexcharts-menu-item.exportSVG,
        .chart-finding-wrapper .apexcharts-menu-item.exportCSV {
            display: none;
        }

        .chart-finding-wrapper .apexcharts-title-text {
            white-space: normal;
        }
        ::-webkit-scrollbar {
            width: 10px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 5px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
    </style>
@endpush
@section('content')
    <div class="container mb-6" style="padding: 0 15px;">
        <div class="row card-progress-wrapper">
            <div class="col-md-12" style="overflow-x: auto; white-space: nowrap;">
                <div class="d-flex">
                    <div class="col-xl-4 col-md-6 col-sm-12 d-inline-block">
                        <div class="card card-custom gutter-b card-stretch wave wave-primary" data-name="pengajuan-tad">
                            <div class="card-body">
                                <div class="d-flex flex-wrap align-items-center py-1">
                                    <div class="symbol symbol-40 symbol-light-primary mr-5">
                                        <span class="symbol-label shadow">
                                            <i class="fa fa-user-edit align-self-center text-primary font-size-h5"></i>
                                        </span>
                                    </div>
                                    <div class="d-flex flex-column flex-grow-1 my-lg-0 my-2 pr-3">
                                        <div class="text-dark font-weight-bolder font-size-h6">
                                            Pengajuan TAD
                                        </div>
                                        <div class="text-muted font-weight-bold font-size-lg">
                                            <div class="d-flex justify-content-between">
                                                <span class="text-nowrap">Selesai/Total</span>
                                                <span class="text-nowrap">
                                                    <span class="completed">{{ $pengajuan_tad_completed }}</span>
                                                    /
                                                    <span class="total">{{ $pengajuan_tad_all }}</span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-column w-100 mt-5">
                                        <div class="text-dark mr-2 font-size-lg font-weight-bolder pb-3">
                                            <div class="d-flex justify-content-between">
                                                <span
                                                    class="percent-text">{{ $pengajuan_tad_all == 0 ? 0 : ($pengajuan_tad_completed / $pengajuan_tad_all) * 100 }}%</span>
                                            </div>
                                        </div>
                                        <div class="progress progress-xs w-100">
                                            <div class="progress-bar percent-bar" role="progressbar"
                                                style="width: {{ $pengajuan_tad_all == 0 ? 0 : ($pengajuan_tad_completed / $pengajuan_tad_all) * 100 }}%;">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-md-6 col-sm-12 d-inline-block">
                        <div class="card card-custom gutter-b card-stretch wave wave-danger" data-name="pengajuan-resign">
                            <div class="card-body">
                                <div class="d-flex flex-wrap align-items-center py-1">
                                    <div class="symbol symbol-40 symbol-light-danger mr-5">
                                        <span class="symbol-label shadow">
                                            <i class="fas fa-sign-out-alt align-self-center text-danger font-size-h5"></i>
                                        </span>
                                    </div>
                                    @php
                                        $pengajuan_resign_completed = $pengajuan_resign->where('status', 'completed')->count();
                                        $pengajuan_resign_all = $pengajuan_resign->count();
                                    @endphp
                                    <div class="d-flex flex-column flex-grow-1 my-lg-0 my-2 pr-3">
                                        <div class="text-dark font-weight-bolder font-size-h6">
                                            Pengajuan Resign
                                        </div>
                                        <div class="text-muted font-weight-bold font-size-lg">
                                            <div class="d-flex justify-content-between">
                                                <span class="text-nowrap">Selesai/Total</span>
                                                <span class="text-nowrap">
                                                    <span class="completed">{{ $pengajuan_resign_completed }}</span>
                                                    /
                                                    <span class="total">{{ $pengajuan_resign_all }}</span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-column w-100 mt-5">
                                        <div class="text-dark mr-2 font-size-lg font-weight-bolder pb-3">
                                            <div class="d-flex justify-content-between">
                                                <span
                                                    class="percent-text">
                                                    @if($pengajuan_resign_all == 0)
                                                        0%
                                                    @else
                                                        {{$pengajuan_resign_completed / $pengajuan_resign_all * 100 }}%
                                                    @endif
                                                </span>
                                            </div>
                                        </div>
                                        <div class="progress progress-xs w-100">
                                            <div class="progress-bar percent-bar" role="progressbar"
                                                style="width: @if($pengajuan_resign_all == 0)
                                                        0%
                                                    @else
                                                        {{$pengajuan_resign_completed / $pengajuan_resign_all * 100 }}%
                                                    @endif">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-md-6 col-sm-12 d-inline-block">
                        <div class="card card-custom gutter-b card-stretch wave wave-success" data-name="pengajuan-mutasi">
                            <div class="card-body">
                                <div class="d-flex flex-wrap align-items-center py-1">
                                    <div class="symbol symbol-40 symbol-light-secondary mr-5">
                                        <span class="symbol-label shadow">
                                            <i class="fas fa-sign-in-alt align-self-center text-success font-size-h5"></i>
                                        </span>
                                    </div>
                                    @php
                                        $pengajuan_mutasi_completed = $pengajuan_mutasi->where('status', 'completed')->count();
                                        $pengajuan_mutasi_all = $pengajuan_mutasi->count();
                                    @endphp
                                    <div class="d-flex flex-column flex-grow-1 my-lg-0 my-2 pr-3">
                                        <div class="text-dark font-weight-bolder font-size-h6">
                                            Pengajuan Mutasi
                                        </div>
                                        <div class="text-muted font-weight-bold font-size-lg">
                                            <div class="d-flex justify-content-between">
                                                <span class="text-nowrap">Selesai/Total</span>
                                                <span class="text-nowrap">
                                                    <span class="completed">{{ $pengajuan_mutasi_completed }}</span>
                                                    /
                                                    <span class="total">{{ $pengajuan_mutasi_all }}</span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-column w-100 mt-5">
                                        <div class="text-dark mr-2 font-size-lg font-weight-bolder pb-3">
                                            <div class="d-flex justify-content-between">
                                                <span
                                                    class="percent-text">
                                                    @if($pengajuan_mutasi_all == 0)
                                                        0%
                                                    @else
                                                        {{$pengajuan_mutasi_completed / $pengajuan_mutasi_all * 100 }}%
                                                    @endif
                                                    </span>
                                            </div>
                                        </div>
                                        <div class="progress progress-xs w-100">
                                            <div class="progress-bar percent-bar" role="progressbar"
                                                style="width:
                                                @if($pengajuan_mutasi_all == 0)
                                                    0%
                                                @else
                                                    {{$pengajuan_mutasi_completed / $pengajuan_mutasi_all * 100 }}%
                                                @endif
                                                ">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-md-6 col-sm-12 d-inline-block">
                        <div class="card card-custom gutter-b card-stretch wave wave-warning" data-name="penilaian-tad">
                            <div class="card-body">
                                <div class="d-flex flex-wrap align-items-center py-1">
                                    <div class="symbol symbol-40 symbol-light-warning mr-5">
                                        <span class="symbol-label shadow">
                                            <i class="fas fa-star align-self-center text-warning font-size-h5"></i>
                                        </span>
                                    </div>
                                    @php
                                        $penilaian_tad_completed = $penilaian_tad->where('status', 'completed')->count();
                                        $penilaian_tad_all = $penilaian_tad->count();
                                    @endphp
                                    <div class="d-flex flex-column flex-grow-1 my-lg-0 my-2 pr-3">
                                        <div class="text-dark font-weight-bolder font-size-h6">
                                            Penilaian TAD
                                        </div>
                                        <div class="text-muted font-weight-bold font-size-lg">
                                            <div class="d-flex justify-content-between">
                                                <span class="text-nowrap">Selesai/Total</span>
                                                <span class="text-nowrap">
                                                    <span class="completed">{{ $penilaian_tad_completed }}</span>
                                                    /
                                                    <span class="total">{{ $penilaian_tad_all }}</span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-column w-100 mt-5">
                                        <div class="text-dark mr-2 font-size-lg font-weight-bolder pb-3">
                                            <div class="d-flex justify-content-between">
                                                <span
                                                    class="percent-text">
                                                    @if($penilaian_tad_all == 0)
                                                        0%
                                                    @else
                                                        {{$penilaian_tad_completed / $penilaian_tad_all * 100 }}%
                                                    @endif
                                                    </span>
                                            </div>
                                        </div>
                                        <div class="progress progress-xs w-100">
                                            <div class="progress-bar percent-bar" role="progressbar"
                                                style="width: @if($penilaian_tad_all == 0)
                                                        0%
                                                    @else
                                                        {{$penilaian_tad_completed / $penilaian_tad_all * 100 }}%
                                                    @endif
                                                ;">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- additional card -->
                    <div class="col-xl-4 col-md-6 col-sm-12 d-inline-block">
                        <div class="card card-custom gutter-b card-stretch wave wave-primary" data-name="penilaian-vendor">
                            <div class="card-body">
                                <div class="d-flex flex-wrap align-items-center py-1">
                                    <div class="symbol symbol-40 symbol-light-primary mr-5">
                                        <span class="symbol-label shadow">
                                            <i class="fa fa-user-edit align-self-center text-primary font-size-h5"></i>
                                        </span>
                                    </div>
                                    @php
                                        $penilaian_vendor_completed = $penilaian_vendor->where('status', 'completed')->count();
                                        $penilaian_vendor_all = $penilaian_vendor->count();
                                    @endphp
                                    <div class="d-flex flex-column flex-grow-1 my-lg-0 my-2 pr-3">
                                        <div class="text-dark font-weight-bolder font-size-h6">
                                            Penilaian Vendor
                                        </div>
                                        <div class="text-muted font-weight-bold font-size-lg">
                                            <div class="d-flex justify-content-between">
                                                <span class="text-nowrap">Selesai/Total</span>
                                                <span class="text-nowrap">
                                                    <span class="completed">{{ $penilaian_vendor_completed }}</span>
                                                    /
                                                    <span class="total">{{ $penilaian_vendor_all }}</span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-column w-100 mt-5">
                                        <div class="text-dark mr-2 font-size-lg font-weight-bolder pb-3">
                                            <div class="d-flex justify-content-between">
                                                <span
                                                    class="percent-text">{{ $penilaian_vendor_all == 0 ? 0 : ($penilaian_vendor_completed / $penilaian_vendor_all) * 100 }}%</span>
                                            </div>
                                        </div>
                                        <div class="progress progress-xs w-100">
                                            <div class="progress-bar percent-bar" role="progressbar"
                                                style="width: {{ $penilaian_vendor_all == 0 ? 0 : ($penilaian_vendor_completed / $penilaian_vendor_all) * 100 }}%;">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-md-6 col-sm-12 d-inline-block">
                        <div class="card card-custom gutter-b card-stretch wave wave-danger" data-name="perpanjangan-kontrak">
                            <div class="card-body">
                                <div class="d-flex flex-wrap align-items-center py-1">
                                    <div class="symbol symbol-40 symbol-light-danger mr-5">
                                        <span class="symbol-label shadow">
                                            <i class="fas fa-sign-out-alt align-self-center text-danger font-size-h5"></i>
                                        </span>
                                    </div>
                                    @php
                                        $perpanjangan_kontrak_completed = $perpanjangan_kontrak->where('status', 'completed')->count();
                                        $perpanjangan_kontrak_all = $perpanjangan_kontrak->count();
                                    @endphp
                                    <div class="d-flex flex-column flex-grow-1 my-lg-0 my-2 pr-3">
                                        <div class="text-dark font-weight-bolder font-size-h6">
                                            Kontrak
                                        </div>
                                        <div class="text-muted font-weight-bold font-size-lg">
                                            <div class="d-flex justify-content-between">
                                                <span class="text-nowrap">Selesai/Total</span>
                                                <span class="text-nowrap">
                                                    <span class="completed">{{ $perpanjangan_kontrak_completed }}</span>
                                                    /
                                                    <span class="total">{{ $perpanjangan_kontrak_all }}</span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-column w-100 mt-5">
                                        <div class="text-dark mr-2 font-size-lg font-weight-bolder pb-3">
                                            <div class="d-flex justify-content-between">
                                                <span
                                                    class="percent-text">
                                                    @if($perpanjangan_kontrak_all == 0)
                                                        0%
                                                    @else
                                                        {{$perpanjangan_kontrak_completed / $perpanjangan_kontrak_all * 100 }}%
                                                    @endif
                                                </span>
                                            </div>
                                        </div>
                                        <div class="progress progress-xs w-100">
                                            <div class="progress-bar percent-bar" role="progressbar"
                                                style="width: @if($perpanjangan_kontrak_all == 0)
                                                        0%
                                                    @else
                                                        {{$perpanjangan_kontrak_completed / $perpanjangan_kontrak_all * 100 }}%
                                                    @endif">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-md-6 col-sm-12 d-inline-block">
                        <div class="card card-custom gutter-b card-stretch wave wave-success" data-name="penghargaan_tad">
                            <div class="card-body">
                                <div class="d-flex flex-wrap align-items-center py-1">
                                    <div class="symbol symbol-40 symbol-light-secondary mr-5">
                                        <span class="symbol-label shadow">
                                            <i class="fas fa-sign-in-alt align-self-center text-success font-size-h5"></i>
                                        </span>
                                    </div>
                                    @php
                                        $penghargaan_tad_completed = $penghargaan_tad->where('status', 'completed')->count();
                                        $penghargaan_tad_all = $penghargaan_tad->count();
                                    @endphp
                                    <div class="d-flex flex-column flex-grow-1 my-lg-0 my-2 pr-3">
                                        <div class="text-dark font-weight-bolder font-size-h6">
                                            Penghargaan
                                        </div>
                                        <div class="text-muted font-weight-bold font-size-lg">
                                            <div class="d-flex justify-content-between">
                                                <span class="text-nowrap">Selesai/Total</span>
                                                <span class="text-nowrap">
                                                    <span class="completed">{{ $penghargaan_tad_completed }}</span>
                                                    /
                                                    <span class="total">{{ $penghargaan_tad_all }}</span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-column w-100 mt-5">
                                        <div class="text-dark mr-2 font-size-lg font-weight-bolder pb-3">
                                            <div class="d-flex justify-content-between">
                                                <span
                                                    class="percent-text">
                                                    @if($penghargaan_tad_all == 0)
                                                        0%
                                                    @else
                                                        {{$penghargaan_tad_completed / $penghargaan_tad_all * 100 }}%
                                                    @endif
                                                    </span>
                                            </div>
                                        </div>
                                        <div class="progress progress-xs w-100">
                                            <div class="progress-bar percent-bar" role="progressbar"
                                                style="width:
                                                @if($penghargaan_tad_all == 0)
                                                    0%
                                                @else
                                                    {{$penghargaan_tad_completed / $penghargaan_tad_all * 100 }}%
                                                @endif
                                                ">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-md-6 col-sm-12 d-inline-block">
                        <div class="card card-custom gutter-b card-stretch wave wave-warning" data-name="pembinaan-tad">
                            <div class="card-body">
                                <div class="d-flex flex-wrap align-items-center py-1">
                                    <div class="symbol symbol-40 symbol-light-warning mr-5">
                                        <span class="symbol-label shadow">
                                            <i class="fas fa-star align-self-center text-warning font-size-h5"></i>
                                        </span>
                                    </div>
                                    @php
                                        $pembinaan_tad_completed = $pembinaan_tad->where('status', 'completed')->count();
                                        $pembinaan_tad_all = $pembinaan_tad->count();
                                    @endphp
                                    <div class="d-flex flex-column flex-grow-1 my-lg-0 my-2 pr-3">
                                        <div class="text-dark font-weight-bolder font-size-h6">
                                            Pembinaan
                                        </div>
                                        <div class="text-muted font-weight-bold font-size-lg">
                                            <div class="d-flex justify-content-between">
                                                <span class="text-nowrap">Selesai/Total</span>
                                                <span class="text-nowrap">
                                                    <span class="completed">{{ $pembinaan_tad_completed }}</span>
                                                    /
                                                    <span class="total">{{ $pembinaan_tad_all }}</span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-column w-100 mt-5">
                                        <div class="text-dark mr-2 font-size-lg font-weight-bolder pb-3">
                                            <div class="d-flex justify-content-between">
                                                <span
                                                    class="percent-text">
                                                    @if($pembinaan_tad_all == 0)
                                                        0%
                                                    @else
                                                        {{$pembinaan_tad_completed / $pembinaan_tad_all * 100 }}%
                                                    @endif
                                                    </span>
                                            </div>
                                        </div>
                                        <div class="progress progress-xs w-100">
                                            <div class="progress-bar percent-bar" role="progressbar"
                                                style="width: @if($pembinaan_tad_all == 0)
                                                        0%
                                                    @else
                                                        {{$pembinaan_tad_completed / $pembinaan_tad_all * 100 }}%
                                                    @endif
                                                ;">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="card card-custom card-stretch gutter-b chart-finding-wrapper">
                <div class="card-header h-auto py-3">
                    <div class="card-title">
                        <h3 class="card-label">
                            <span class="d-block text-dark font-weight-bolder">{{ __('Quota') }}</span>
                        </h3>
                    </div>
                    <div class="card-toolbar" style="max-width: 350px;">
                        <form id="filter-chart-finding" action="{{ route($route . '.quota') }}" method="post" class="form-inline"
                            role="form">
                            @csrf
                            <div class="row">
                                <div class="col-md-4 col-sm-12 mr-n6">
                                    <div class="input-daterange input-group text-nowrap">
                                        <input class="form-control quota_tahun" id="quotaTahun" name="quota_tahun"
                                            value="{{ request()->quota_tahun ?? date('Y') }}" style="">
                                    </div>
                                </div>
                                <div class="col-md-7 col-sm-12">
                                    <select class="form-control base-plugin--select2 quota_unit"
                                        data-placeholder="{{ __('Unit Kerja') }}" id="quotaUnit" name="quota_unit">
                                        <option value="">{{ __('Semua Unit Kerja') }}</option>
                                        @foreach (\Modules\Master\Entities\SO\OrgStruct::groupByLevel() as $level => $group)
                                            @foreach ($group as $val)
                                                @if ($loop->first)
                                                    <optgroup
                                                        label="{{ \Modules\Master\Entities\SO\OrgStruct::getLevelLabel($level) }}">
                                                @endif
                                                <option value="{{ $val->id }}">{{ $val->name }}</option>
                                                @if ($loop->last)
                                                    </optgroup>
                                                @endif
                                            @endforeach
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-1 col-sm-12">
                                    <div class="form-inline align-items-center" id="resetFilterQuota">
                                        <div class="reset-filter-quota mr-sm-2" id="reset-filter-quota" style="display:none;">
                                            <div class="btn btn-info btn-pill btn-icon btn-sm" data-toggle="tooltip"
                                                data-original-title="Reset Filter"><i class="icon-md la la-refresh"></i></div>
                                        </div>
                                        <div class="label-filter-quota mr-sm-2">
                                            <div class="btn btn-primary btn-pill btn-icon btn-sm" data-toggle="tooltip"
                                                data-original-title="Filter"><i class="icon-md text-white la la-filter"></i></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-wrapper">
                        <div id="chart-finding">
                            <div class="d-flex h-100">
                                <div class="spinners m-auto my-auto">
                                    <div class="spinner-grow text-success" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                    <div class="spinner-grow text-danger" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                    <div class="spinner-grow text-warning" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- Kinerja/Penilaian --}}
        <div class="col-md-6">
            <div class="card card-custom card-stretch gutter-b chart-penilaian-wrapper">
                <div class="card-header h-auto py-3">
                    <div class="card-title">
                        <h3 class="card-label">
                            <span class="d-block text-dark font-weight-bolder">{{ __('Penilaian') }}</span>
                        </h3>
                    </div>
                    <div class="card-toolbar" style="max-width: 350px;">
                        <form id="filter-chart-penilaian" action="{{ route($route . '.penilaian') }}" class="form-inline"
                            role="form">
                            <div class="row">
                                <div class="col-md-4 col-sm-12 mr-n6">
                                    <div class="input-daterange input-group text-nowrap">
                                        <input class="form-control penilaian_tahun" id="" name="penilaian_tahun"
                                            value="{{ request()->penilaian_tahun ?? date('Y') }}" style="">
                                    </div>
                                </div>
                                <div class="col-md-7 col-sm-12">
                                    <select class="form-control base-plugin--select2 penilaian_unit"
                                        data-placeholder="{{ __('Unit Kerja') }}" id="penilaianUnit" name="penilaian_unit">
                                        <option value="">{{ __('Unit Kerja') }}</option>
                                        @foreach (\Modules\Master\Entities\SO\OrgStruct::groupByLevel() as $level => $group)
                                            @foreach ($group as $val)
                                                @if ($loop->first)
                                                    <optgroup
                                                        label="{{ \Modules\Master\Entities\SO\OrgStruct::getLevelLabel($level) }}">
                                                @endif
                                                <option value="{{ $val->id }}">{{ $val->name }}</option>
                                                @if ($loop->last)
                                                    </optgroup>
                                                @endif
                                            @endforeach
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-1 col-sm-12">
                                    <div class="form-inline align-items-center" id="resetFilterPenilaian">
                                        <div class="reset-filter-penilaian mr-sm-2" id="reset-filter-penilaian" style="display:none;">
                                            <div class="btn btn-info btn-pill btn-icon btn-sm" data-toggle="tooltip"
                                                data-original-title="Reset Filter"><i class="icon-md la la-refresh"></i></div>
                                        </div>
                                        <div class="label-filter-penilaian mr-sm-2">
                                            <div class="btn btn-primary btn-pill btn-icon btn-sm" data-toggle="tooltip"
                                                data-original-title="Filter"><i class="icon-md text-white la la-filter"></i></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-wrapper">
                        <div id="chart-penilaian">
                            <div class="d-flex h-100">
                                <div class="spinners m-auto my-auto">
                                    <div class="spinner-grow text-success" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                    <div class="spinner-grow text-danger" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                    <div class="spinner-grow text-warning" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- Resign --}}
        <div class="col-md-6">
            <div class="card card-custom card-stretch gutter-b chart-resign-wrapper">
                <div class="card-header h-auto py-3">
                    <div class="card-title">
                        <h3 class="card-label">
                            <span class="d-block text-dark font-weight-bolder">{{ __('Resign') }}</span>
                        </h3>
                    </div>
                    <div class="card-toolbar" style="max-width: 350px;">
                        <form id="filter-chart-resign" action="{{ route($route . '.resign') }}" class="form-inline"
                            role="form">
                            <div class="row">
                                <div class="col-md-4 col-sm-12 mr-n6">
                                    <div class="input-daterange input-group text-nowrap">
                                        <input class="form-control resign_tahun" id="" name="resign_tahun"
                                            value="{{ request()->resign_tahun ?? date('Y') }}" style="">
                                    </div>
                                </div>
                                <div class="col-md-7 col-sm-12">
                                    <select class="form-control base-plugin--select2 resign_unit"
                                        data-placeholder="{{ __('Unit Kerja') }}" id="resignUnit" name="resign_unit">
                                        <option value="">{{ __('Unit Kerja') }}</option>
                                        @foreach (\Modules\Master\Entities\SO\OrgStruct::groupByLevel() as $level => $group)
                                            @foreach ($group as $val)
                                                @if ($loop->first)
                                                    <optgroup
                                                        label="{{ \Modules\Master\Entities\SO\OrgStruct::getLevelLabel($level) }}">
                                                @endif
                                                <option value="{{ $val->id }}">{{ $val->name }}</option>
                                                @if ($loop->last)
                                                    </optgroup>
                                                @endif
                                            @endforeach
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-1 col-sm-12">
                                    <div class="form-inline align-items-center" id="resetFilterResign">
                                        <div class="reset-filter-resign mr-sm-2" id="reset-filter-resign" style="display:none;">
                                            <div class="btn btn-info btn-pill btn-icon btn-sm" data-toggle="tooltip"
                                                data-original-title="Reset Filter"><i class="icon-md la la-refresh"></i></div>
                                        </div>
                                        <div class="label-filter-resign mr-sm-2">
                                            <div class="btn btn-primary btn-pill btn-icon btn-sm" data-toggle="tooltip"
                                                data-original-title="Filter"><i class="icon-md text-white la la-filter"></i></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-wrapper">
                        <div id="chart-resign">
                            <div class="d-flex h-100">
                                <div class="spinners m-auto my-auto">
                                    <div class="spinner-grow text-success" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                    <div class="spinner-grow text-danger" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                    <div class="spinner-grow text-warning" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- Mutasi --}}
        <div class="col-md-6">
            <div class="card card-custom card-stretch gutter-b chart-mutasi-wrapper">
                <div class="card-header h-auto py-3">
                    <div class="card-title">
                        <h3 class="card-label">
                            <span class="d-block text-dark font-weight-bolder">{{ __('Mutasi') }}</span>
                        </h3>
                    </div>
                    <div class="card-toolbar" style="max-width: 350px;">
                        <form id="filter-chart-mutasi" action="{{ route($route . '.mutasi') }}" class="form-inline"
                            role="form">
                            <div class="row">
                                <div class="col-md-4 col-sm-12 mr-n6">
                                    <div class="input-daterange input-group text-nowrap">
                                        <input class="form-control mutasi_tahun" id="" name="mutasi_tahun"
                                            value="{{ request()->mutasi_tahun ?? date('Y') }}" style="">
                                    </div>
                                </div>
                                <div class="col-md-7 col-sm-12">
                                    <select class="form-control base-plugin--select2 mutasi_unit"
                                        data-placeholder="{{ __('Unit Kerja') }}" id="mutasiUnit" name="mutasi_unit">
                                        <option value="">{{ __('Unit Kerja') }}</option>
                                        @foreach (\Modules\Master\Entities\SO\OrgStruct::groupByLevel() as $level => $group)
                                            @foreach ($group as $val)
                                                @if ($loop->first)
                                                    <optgroup
                                                        label="{{ \Modules\Master\Entities\SO\OrgStruct::getLevelLabel($level) }}">
                                                @endif
                                                <option value="{{ $val->id }}">{{ $val->name }}</option>
                                                @if ($loop->last)
                                                    </optgroup>
                                                @endif
                                            @endforeach
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-1 col-sm-12">
                                    <div class="form-inline align-items-center" id="resetFilterMutasi">
                                        <div class="reset-filter-mutasi mr-sm-2" id="reset-filter-mutasi" style="display:none;">
                                            <div class="btn btn-info btn-pill btn-icon btn-sm" data-toggle="tooltip"
                                                data-original-title="Reset Filter"><i class="icon-md la la-refresh"></i></div>
                                        </div>
                                        <div class="label-filter-mutasi mr-sm-2">
                                            <div class="btn btn-primary btn-pill btn-icon btn-sm" data-toggle="tooltip"
                                                data-original-title="Filter"><i class="icon-md text-white la la-filter"></i></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-wrapper">
                        <div id="chart-mutasi">
                            <div class="d-flex h-100">
                                <div class="spinners m-auto my-auto">
                                    <div class="spinner-grow text-success" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                    <div class="spinner-grow text-danger" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                    <div class="spinner-grow text-warning" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function toggleResetButtonQuota() {
            var quotaTahun = $('.quota_tahun').val();
            var quotaUnit = $('.quota_unit').val();

            if (quotaTahun != '{{ date('Y') }}' || quotaUnit != '') {
                $('.reset-filter-quota').show();
                $('.label-filter-quota').hide();
            } else {
                $('.reset-filter-quota').hide();
                $('.label-filter-quota').show();
            }
        }

        function toggleResetButtonPenilaian() {
            var penilaianTahun = $('.penilaian_tahun').val();
            var penilaianUnit = $('.penilaian_unit').val();

            if (penilaianTahun != '{{ date('Y') }}' || penilaianUnit != '') {
                $('.reset-filter-penilaian').show();
                $('.label-filter-penilaian').hide();
            } else {
                $('.reset-filter-penilaian').hide();
                $('.label-filter-penilaian').show();
            }
        }

        function toggleResetButtonResign() {
            var resignTahun = $('.resign_tahun').val();
            var resignUnit = $('.resign_unit').val();

            if (resignTahun != '{{ date('Y') }}' || resignUnit != '') {
                $('.reset-filter-resign').show();
                $('.label-filter-resign').hide();
            } else {
                $('.reset-filter-resign').hide();
                $('.label-filter-resign').show();
            }
        }

        function toggleResetButtonMutasi() {
            var mutasiTahun = $('.mutasi_tahun').val();
            var mutasiUnit = $('.mutasi_unit').val();

            if (mutasiTahun != '{{ date('Y') }}' || mutasiUnit != '') {
                $('.reset-filter-mutasi').show();
                $('.label-filter-mutasi').hide();
            } else {
                $('.reset-filter-mutasi').hide();
                $('.label-filter-mutasi').show();
            }
        }

        function initFilterChart() {
            $('input.quota_tahun').datepicker({
                    format: "yyyy",
                    viewMode: "years",
                    minViewMode: "years",
                    orientation: "bottom auto",
                    autoclose: true
                })
                .on('changeDate', function(value) {
                    drawChartQuota();
                });
            $('input.penilaian_tahun').datepicker({
                    format: "yyyy",
                    viewMode: "years",
                    minViewMode: "years",
                    orientation: "bottom auto",
                    autoclose: true
                })
                .on('changeDate', function(value) {
                    drawChartPenilaian();
                });
            $('input.resign_tahun').datepicker({
                format: "yyyy",
                viewMode: "years",
                minViewMode: "years",
                orientation: "bottom auto",
                autoclose: true
            })
            .on('changeDate', function(value) {
                drawChartResign();
            });

            $('input.mutasi_tahun').datepicker({
                format: "yyyy",
                viewMode: "years",
                minViewMode: "years",
                orientation: "bottom auto",
                autoclose: true
            })
            .on('changeDate', function(value) {
                drawChartMutasi();
            });

            $(document)
                .on('change', '.quota_tahun, .quota_unit', function() {
                    toggleResetButtonQuota();
                    drawChartQuota();
                })
                .on('change', '.penilaian_tahun, .penilaian_unit', function() {
                    toggleResetButtonPenilaian();
                    drawChartPenilaian();
                })
                .on('change', '.resign_tahun, .resign_unit', function() {
                    toggleResetButtonResign();
                    drawChartResign();
                })
                .on('change', '.mutasi_tahun, .mutasi_unit', function() {
                    toggleResetButtonMutasi();
                    drawChartMutasi();
                })
                .on('click', '#resetFilterQuota', function() {
                    $('.quota_tahun').val('{{ date('Y') }}');
                    $('.quota_unit').val("");
                    drawChartQuota();
                    toggleResetButtonQuota();
                    BasePlugin.init();

                })
                .on('click', '#resetFilterPenilaian', function() {
                    $('.penilaian_tahun').val('{{ date('Y') }}');
                    $('.penilaian_unit').val("");
                    drawChartPenilaian();
                    toggleResetButtonPenilaian();
                    BasePlugin.init();

                })
                .on('click', '#resetFilterResign', function() {
                    $('.resign_tahun').val('{{ date('Y') }}');
                    $('.resign_unit').val("");
                    drawChartResign();
                    toggleResetButtonResign();
                    BasePlugin.init();

                })
                .on('click', '#resetFilterMutasi', function() {
                    $('.mutasi_tahun').val('{{ date('Y') }}');
                    $('.mutasi_unit').val("");
                    drawChartMutasi();
                    toggleResetButtonMutasi();
                    BasePlugin.init();

                });
        }

        function drawChartQuota() {
            var filter = $('#filter-chart-finding');
            $.ajax({
                url: filter.attr('action'),
                method: 'POST',
                data: {
                    _token: BaseUtil.getToken(),
                    quota_tahun: filter.find('.quota_tahun').val(),
                    quota_semester: filter.find('.quota_semester').val(),
                    quota_unit: filter.find('.quota_unit').val(),
                },
                success: function(resp) {
                    $('.chart-finding-wrapper .chart-wrapper').find('#chart-finding').remove();
                    $('.chart-finding-wrapper .chart-wrapper').html(`<div id="chart-finding"></div>`);
                    renderChartQuota(resp);
                },
                error: function(resp) {
                    console.log(resp)
                }
            });
        }
        function renderChartQuota(resp = {}) {
            var element = document.getElementById('chart-finding');
            var options = {
                series: resp.series,
                chart: {
                    type: 'bar',
                    height: 350
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: '25%',
                        // endingShape: 'rounded'
                    },
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    show: true,
                    width: 2,
                    colors: ['transparent']
                },
                xaxis: {
                    categories: resp.xaxis.categories,
                },
                yaxis: {
                    title: {
                        text: ''
                    }
                },
                fill: {
                    opacity: 1
                },
                colors:  [
                    KTApp.getSettings()['colors']['theme']['base']['danger'],
                    KTApp.getSettings()['colors']['theme']['base']['success'],
                    KTApp.getSettings()['colors']['theme']['light']['warning'],
                    KTApp.getSettings()['colors']['theme']['base']['warning'],
                    KTApp.getSettings()['colors']['theme']['light']['success'],
                    KTApp.getSettings()['colors']['theme']['base']['success'],
                ],
                tooltip: {
                    y: {
                        formatter: function(val) {
                            return val;
                        }
                    }
                }
            };
            var chart = new ApexCharts(element, options);
            chart.render();
        }

        // Penilaian
        function drawChartPenilaian() {
            var filter = $('#filter-chart-penilaian');
            $.ajax({
                url: filter.attr('action'),
                method: 'POST',
                data: {
                    _token              : BaseUtil.getToken(),
                    penilaian_tahun     : filter.find('.penilaian_tahun').val(),
                    penilaian_semester  : filter.find('.penilaian_semester').val(),
                    penilaian_unit      : filter.find('.penilaian_unit').val(),
                },
                success: function(resp) {
                    $('#chart-penilaian').remove();
                    $('.chart-penilaian-wrapper .chart-wrapper').html(`<div id="chart-penilaian"></div>`);
                    renderChartPenilaian(resp);
                },
                error: function(resp) {
                    console.log(resp)
                }
            });
        }
        function renderChartPenilaian(resp = {}) {
            console.log(448, resp);
            var element = document.getElementById('chart-penilaian');
            var options = {
                series: resp.series,
                chart: {
                    type: 'bar',
                    height: 350
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: '25%',
                        // endingShape: 'rounded'
                    },
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    show: true,
                    width: 2,
                    colors: ['transparent']
                },
                xaxis: {
                    categories: resp.xaxis.categories,
                },
                yaxis: {
                    title: {
                        text: ''
                    }
                },
                fill: {
                    opacity: 1
                },
                tooltip: {
                    y: {
                        formatter: function(val) {
                            return val;
                        }
                    }
                }
            };
            var chart = new ApexCharts(element, options);
            chart.render();
        }

        // Resign
        function drawChartResign() {
            var filter = $('#filter-chart-resign');
            $.ajax({
                url: filter.attr('action'),
                method: 'POST',
                data: {
                    _token              : BaseUtil.getToken(),
                    resign_tahun     : filter.find('.resign_tahun').val(),
                    resign_semester  : filter.find('.resign_semester').val(),
                    resign_unit      : filter.find('.resign_unit').val(),
                },
                success: function(resp) {
                    $('#chart-resign').remove();
                    $('.chart-resign-wrapper .chart-wrapper').html(`<div id="chart-resign"></div>`);
                    renderChartResign(resp);
                },
                error: function(resp) {
                    console.log(resp)
                }
            });
        }
        function renderChartResign(resp = {}) {
            console.log(448, resp);
            var element = document.getElementById('chart-resign');
            var options = {
                series: resp.series,
                chart: {
                    type: 'bar',
                    height: 350
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: '25%',
                        // endingShape: 'rounded'
                    },
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    show: true,
                    width: 2,
                    colors: ['transparent']
                },
                xaxis: {
                    categories: resp.xaxis.categories,
                },
                yaxis: {
                    title: {
                        text: ''
                    }
                },
                fill: {
                    opacity: 1
                },
                tooltip: {
                    y: {
                        formatter: function(val) {
                            return val;
                        }
                    }
                }
            };
            var chart = new ApexCharts(element, options);
            chart.render();
        }

        // Mutasi
        function drawChartMutasi() {
            var filter = $('#filter-chart-mutasi');
            $.ajax({
                url: filter.attr('action'),
                method: 'POST',
                data: {
                    _token              : BaseUtil.getToken(),
                    mutasi_tahun     : filter.find('.mutasi_tahun').val(),
                    mutasi_semester  : filter.find('.mutasi_semester').val(),
                    mutasi_unit      : filter.find('.mutasi_unit').val(),
                },
                success: function(resp) {
                    $('#chart-mutasi').remove();
                    $('.chart-mutasi-wrapper .chart-wrapper').html(`<div id="chart-mutasi"></div>`);
                    renderChartMutasi(resp);
                },
                error: function(resp) {
                    console.log(resp)
                }
            });
        }
        function renderChartMutasi(resp = {}) {
            console.log(448, resp);
            var element = document.getElementById('chart-mutasi');
            var options = {
                series: resp.series,
                chart: {
                    type: 'bar',
                    height: 350
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: '25%',
                        // endingShape: 'rounded'
                    },
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    show: true,
                    width: 2,
                    colors: ['transparent']
                },
                xaxis: {
                    categories: resp.xaxis.categories,
                },
                yaxis: {
                    title: {
                        text: ''
                    }
                },
                fill: {
                    opacity: 1
                },
                tooltip: {
                    y: {
                        formatter: function(val) {
                            return val;
                        }
                    }
                }
            };
            var chart = new ApexCharts(element, options);
            chart.render();
        }

        $(document).ready(function() {
            initFilterChart();
            drawChartQuota();
            drawChartPenilaian();
            drawChartResign();
            drawChartMutasi();
        });
    </script>
@endpush
