@push('styles')
    <style>
        .select2-selection__rendered[title="Pilih Personil TAD"] {
            color: #B5B5C3 !important;
        }
    </style>
@endpush
@extends('layouts.app')
@section('title', $title)
@section('buttons') @endsection
@section('content')
    <div class="card card-custom">
        <div class="card-header">
            <div class="card-toolbar">
                <ul class="nav nav-light-danger nav-bold nav-pills">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#pengajuan">
                            <span class="nav-icon"><i class="fas fa-user-edit"></i></span>
                            <span class="nav-text">Reward</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#riwayat">
                            <span class="nav-icon"><i class="fas fa-code-branch"></i></span>
                            <span class="nav-text">Riwayat</span>
                        </a>
                    </li>
                </ul>
            </div>
            <button aria-label="Close" class="close" data-dismiss="modal"
                onclick="location.href='{{ url()->previous() }}'" type="button">
                <i aria-hidden="true" class="ki ki-close"></i>
            </button>
        </div>
        <div class="card-body">
            <div class="tab-content">
                <div class="tab-pane fade show active" id="pengajuan" role="tabpanel" aria-labelledby="pengajuan">
                    <div class="form-group row">
                        <label for="" class="col-3 text-bold">Nama</label>
                        <label for="" class="col">{{ $record->tad->nama }}</label>
                    </div>
                    <div class="form-group row">
                        <label class="col-3 text-bold">NIO</label>
                        <label class="col" id="nioValue">{{ $record->tad->kepegawaian->nio }}</label>
                    </div>
                    <div class="form-group row">
                        <label class="col-3 text-bold">Posisi TAD</label>
                        <label class="col" id="jabatanValue">{{ $record->tad->jabatan->NM_UNIT }}</label>
                    </div>
                    <div class="form-group row">
                        <label class="col-3 text-bold">Unit Kerja</label>
                        <label class="col" id="unitKerjaValue">{{ $record->tad->kepegawaian->unitKerja->name }}</label>
                    </div>
                    <div class="form-group row">
                        <label class="col-3 text-bold">SK</label>
                        <label for="" class="col">{{ $record->sk }}</label>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-3 text-bold">Tanggal SK</label>
                        <label for="" class="col">{{ $record->tanggal_sk->format('d/m/Y') }}</label>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-3 text-bold">Jenis Pembinaan</label>
                        <label for="" class="col">{{ $record->jenisPunishment->Lengkap }}</label>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-3 text-bold">Eviden</label>
                        <label for="" class="col">{{ nl2br($record->eviden) }}</label>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-3 text-bold">Tanggal Mulai</label>
                        <label for="" class="col">{{ $record->tanggal_mulai->format('d/m/Y') }}</label>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-3 text-bold">Tanggal Selesai</label>
                        <label for="" class="col">{{ $record->tanggal_selesai->format('d/m/Y') }}</label>
                    </div>
                </div>
                <div class="tab-pane fade" id="riwayat" role="tabpanel" aria-labelledby="riwayat">
                    <div class="timeline timeline-6 mt-3">
                        @foreach ($record->logs()->orderBy('created_at', 'desc')->get() as $item)
                            {{-- {{ dd(json_decode($item), json_decode($item->creator)) }} --}}
                            <div class="timeline-item align-items-start">
                                <div class="timeline-label font-weight-bolder text-dark-75">
                                    {{ $item->created_at->format('H:i:s') }}</div>
                                <div class="timeline-badge">
                                    <i class="fa fa-genderless {{ $item->classLogs() }} icon-xl"></i>
                                </div>
                                <div class="font-weight-mormal timeline-content text-muted pl-3">
                                    <span class="text-bold">{{ $item->keterangan }}</span><br>
                                    <span>Pada: {{ $item->created_at->format('d/m/Y H:i:s') }}</span><br>
                                    <span>Oleh: {{ $item->creator->name }} ({{ $item->creator->isEhc ? $item->creator->position_name : 'Vendor '.($item->creator->vendor->nama ?? '') }}) </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <form action="{{ route($route . '.approvalSave', $record->id) }}" method="POST">
                @csrf
                @method('POST')
                <input type="hidden" name="id" value="{{ $record->id }}">
                <div class="d-flex justify-content-between">
                    {{-- <x-btn-draft via="base-form--submit-page" /> --}}
                    {{-- <x-btn-save via="base-form--submit-page" /> --}}
                    <div style="display: hidden">
                        <x-btn-back class="mr-2" url="{{ route($route . '.index') }}" />
                    </div>
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
    </div>
    <br>
    <div class="row">
        @php
            $tipe = 'pu.pembinaan';
        @endphp
        <div class="col-6">
            @include('penilaian::tad.form.flow')
        </div>
    </div>
@endsection
