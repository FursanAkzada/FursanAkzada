@extends('layouts.app')
@section('title', $title)
@section('buttons-after') @endsection
@section('content')
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
            @if (auth()->user()->isVendor)
            <div class="text-right mr-5">
                <a href="{{ route($route . '.detailCreate', $record->id) }}" class="btn btn-info base-modal--render"
                    data-modal-position="default" data-modal-size="modal-xl" data-modal-backdrop="false"
                    data-modal-v-middle="false">
                    <i class="fa fa-plus"></i> Data
                </a>
            </div>
            @endif
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
        @if (in_array($record->status, [1, 'submit', 'waiting', 'waiting.approval']))
            <div class="card-footer p-5">
                <form action="{{ route($route . '.approvalSave', $record->id) }}" method="POST">
                    @csrf
                    @method('POST')
                    <input type="hidden" name="id" value="{{ $record->id }}">
                    <div class="float-right d-flex flex-row">
                        @if ($approval = $record->checkApproval())
                            <input type="hidden" name="approval_id" value="{{ $approval->id }}">
                            <div class="btn-group dropup d-flex align-items-center">
                                <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    <i class="far fa-save mr-2"></i>Approval
                                </button>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <button type="button" class="dropdown-item" data-toggle="modal"
                                        data-target="#rejectModal">
                                        <i class="mr-3 far fa-save text-danger"></i> Reject
                                    </button>
                                    <button type="button" class="dropdown-item base-form--submit-page"
                                        data-swal-confirm="true" data-submit="approved">
                                        <i class="mr-2 far fa-save text-primary"></i> Approve
                                    </button>
                                </div>
                            </div>
                            @include('pengajuan::tad.partials.modal-reject')
                        @endif
                    </div>
                </form>
            </div>
        @endif
    </div>
    <br>
    <div class="row">
        @php
            $tipe = 'pengajuan.tad.kandidat';
        @endphp

        @if (in_array($record->status, [1, 'submit', 'waiting.approval']))
        @else
        <div class="col-12">
            @include('pengajuan::tad.kandidat.flow')
        </div>
        @endif
    </div>
@endsection
