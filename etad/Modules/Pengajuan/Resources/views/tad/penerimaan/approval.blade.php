@extends('layouts.app')
@section('title', $title)
@section('buttons-after') @endsection
@section('content')
    <div class="card card-custom">
        <div class="card-header">
            <h5 class="card-title">
                Detail Penerimaan
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
                                value="{{ $record->wawancara->kandidat->summary->pengajuan->no_tiket }}" disabled>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label">{{ __('Unit Kerja') }}</label>
                        <div class="col-md-8 parent-group">
                            <input class="form-control" type="text" placeholder="{{ __('Unit Kerja') }}"
                                value="{{ $record->wawancara->kandidat->summary->pengajuan->so->name ?? '' }}" disabled>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label">{{ __('Posisi TAD') }}</label>
                        <div class="col-md-8 parent-group">
                            <input class="form-control" type="text" placeholder="{{ __('Posisi TAD') }}"
                                value="{{ $record->wawancara->kandidat->summary->requirement->jabatan->NM_UNIT . ' ( ' . $record->wawancara->kandidat->summary->requirement->jumlah . ' posisi ' . ')' }}"
                                disabled>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label">{{ __('Nama Kandidat') }}</label>
                        <div class="col-md-8 parent-group">
                            <input class="form-control" type="text" placeholder="{{ __('Nama Kandidat') }}"
                                value="{{ $record->wawancara->kandidat->tad->nama }}" disabled>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label">{{ __('Tgl Pengajuan') }}</label>
                        <div class="col-md-8 parent-group">
                            <input class="form-control" type="text" placeholder="{{ __('Tgl Pengajuan') }}"
                                value="{{ $record->wawancara->kandidat->summary->pengajuan->tgl_pengajuan->translatedFormat('d/m/Y') }}"
                                disabled>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label">{{ __('Periode') }}</label>
                        <div class="col-md-8 parent-group">
                            <input class="form-control" type="text" placeholder="{{ __('Periode') }}"
                                value="{{ 'Tahun ' . $record->wawancara->kandidat->summary->pengajuan->year . ' Semester ' . $record->wawancara->kandidat->summary->pengajuan->semester }}"
                                disabled>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label">{{ __('Vendor') }}</label>
                        <div class="col-md-8 parent-group">
                            <input class="form-control" type="text" placeholder="{{ __('Vendor') }}"
                                value="{{ $record->wawancara->kandidat->summary->requirement->vendor->nama }}" disabled>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label">{{ __('Skor Wawancara') }}</label>
                        <div class="col-md-8 parent-group">
                            <input class="form-control" type="text" placeholder="{{ __('Skor Wawancara') }}"
                                value="{{ $record->wawancara->details->sum('value') }}" disabled>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-12">
            <div class="card card-custom gutter-b" style="min-height:165px;">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="col-md-4 col-form-label">{{ __('Hasil Keputusan') }}</label>
                                <div class="col-md-8 parent-group">
                                    <input class="form-control" type="text" placeholder="{{ __('Skor Wawancara') }}"
                                        value="{{ $record->keputusan }}" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="col-md-4 col-form-label">{{ __('Tgl Keputusan') }}</label>
                                <div class="col-md-8 parent-group">
                                    <input type="text" name="tgl_keputusan"
                                        class="form-control filter-control base-plugin--datepicker" data-format="dd/mm/yyyy"
                                        data-options='@json(['endDate' => ''])' placeholder="{{ __('Tgl Keputusan') }}"
                                        @if ($record->tgl_keputusan)
                                    value="{{ $record->tgl_keputusan->translatedFormat('d/m/Y') }}"
                                    @endif disabled>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="col-md-4 col-form-label">{{ __('Tgl Mulai Kontrak') }}</label>
                                <div class="col-md-8 parent-group">
                                    <input type="text" name="start_date_contract"
                                        class="form-control base-plugin--datepicker start-date-contract"
                                        data-format="dd/mm/yyyy" data-options='@json(['endDate' => '', 'format' => 'dd/mm/yyyy'])'
                                        placeholder="{{ __('Tgl Mulai Kontrak') }}" @if ($record->start_date_contract)
                                    value="{{ $record->start_date_contract->format('d/m/Y') }}"
                                    @endif disabled placeholder="{{ __('Tgl Mulai Kontrak') }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="col-md-4 col-form-label">{{ __('Tgl Selesai Kontrak') }}</label>
                                <div class="col-md-8 parent-group">
                                    <input type="text" name="tgl_contractdue"
                                        class="form-control base-plugin--datepicker tgl-contract-due"
                                        data-format="dd/mm/yyyy" data-options='@json(['endDate' => '', 'format' => 'dd/mm/yyyy'])'
                                        placeholder="{{ __('Tgl Selesai Kontrak') }}" @if ($record->tgl_contractdue)
                                    value="{{ $record->tgl_contractdue->format('d/m/Y') }}"
                                    @endif disabled placeholder="{{ __('Tgl Selesai Kontrak') }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="col-md-4 col-form-label">{{ __('NIO') }}</label>
                                <div class="col-md-8 parent-group">
                                    <input type="text" id="nioCtrl" class="form-control" name="nio"
                                        placeholder="NIO" value="{{ $record->nio }}" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="col-md-4 col-form-label">{{ __('No SK') }}</label>
                                <div class="col-md-8 parent-group">
                                    <input id="noSkCtrl" class="form-control" name="no_sk" placeholder="No SK"
                                        value="{{ $record->no_sk }}" maxlength="32" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group row">
                                <label for="" class="col-2 col-form-label">Kalimat Pembuka</label>
                                <div class="col-10 parent-group">
                                    <textarea name="pembukaan" class="base-plugin--summernote-2" data-height="200"
                                        placeholder="{{ __('Kalimat Pembuka') }}" disabled>
                                        @if (!$record->pembukaan)
{!! $record->getPembukaan() !!}
@else
{!! $record->pembukaan !!}
@endif
                                    </textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="" class="col-2 col-form-label">Isi Surat</label>
                                <div class="col-10 parent-group">
                                    <textarea name="isi_surat" class="base-plugin--summernote-2" data-height="200" placeholder="{{ __('Isi Surat') }}"
                                        disabled>
                                        @if (!$record->isi_surat)
{!! $record->getIsiSurat() !!}
@else
{!! $record->isi_surat !!}
@endif
                                    </textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="" class="col-2 col-form-label">Kalimat Penutup</label>
                                <div class="col-10 parent-group">
                                    <textarea name="penutup" class="base-plugin--summernote-2" data-height="200"
                                        placeholder="{{ __('Kalimat Penutup') }}" disabled>
                                        @if (!$record->penutup)
{!! $record->getPenutup() !!}
@else
{!! $record->penutup !!}
@endif
                                    </textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="" class="col-2 col-form-label">Surat Keputusan</label>
                                <div class="col-10">
                                    @foreach ($record->files as $file)
                                        <div class="progress-container w-100" data-uid="{{ $file->id }}">
                                            <div class="alert alert-custom alert-light fade show success-uploaded mb-0 mt-2 px-3 py-2"
                                                role="alert">
                                                <div class="alert-icon">
                                                    <i class="{{ $file->file_icon }}"></i>
                                                </div>
                                                <div class="alert-text text-left">
                                                    <input type="hidden" name="uploads[files_ids][]"
                                                        value="{{ $file->id }}">
                                                    <div>Uploaded File:</div>
                                                    <a href="{{ $file->file_url }}" target="_blank"
                                                        class="text-primary">
                                                        {{ $file->file_name }}
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @if (in_array($record->status, [1, 'submit', 'waiting', 'waiting.approval']))
                    <div class="card-footer p-5">
                        <form action="{{ route($route . '.approvalSave', $record->id) }}" method="POST">
                            @csrf
                            @method('POST')
                            <input type="hidden" name="id" value="{{ $record->id }}">
                            <div class="d-flex float-right flex-row">
                                {{-- <x-btn-back class="mr-2" url="{{ route($route . '.index') }}" />
                        <x-btn-draft style="float:right;" via="base-form--submit-page" /> --}}
                                {{-- <x-btn-save via="base-form--submit-page" /> --}}
                                @if ($approval = $record->checkApproval())
                                    <input type="hidden" name="approval_id" value="{{ $approval->id }}">
                                    <div class="btn-group dropup d-flex align-items-center">
                                        <button type="button" class="btn btn-info dropdown-toggle"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="far fa-save mr-2"></i>Approval
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <button type="button" class="dropdown-item" data-toggle="modal"
                                                data-target="#rejectModal">
                                                <i class="far fa-save text-danger mr-3"></i> Reject
                                            </button>
                                            <button type="button" class="dropdown-item base-form--submit-page"
                                                data-swal-confirm="true" data-submit="approved">
                                                <i class="far fa-save text-primary mr-2"></i> Approve
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
        </div>
    </div>
    <div class="row">
        @php
            $tipe = 'pengajuan.tad.penerimaan';
        @endphp

        @if (in_array($record->status, [1, 'submit', 'waiting.approval']))
        @else
            <div class="col-12">
                @include('pengajuan::tad.penerimaan.flow')
            </div>
        @endif
    </div>
@endsection
