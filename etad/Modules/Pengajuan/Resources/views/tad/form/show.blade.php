{{-- {{ dd(1, $record->status) }} --}}
@extends('layouts.app')
@section('title', $title)
@section('buttons-after') @endsection
@section('content')
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
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#riwayat">
                            <span class="nav-icon"><i class="fas fa-code-branch"></i></span>
                            <span class="nav-text">Riwayat Pengajuan</span>
                        </a>
                    </li>
                </ul>
            </div>
            <button aria-label="Close" class="close" data-dismiss="card" onclick="location.href='{{ url()->previous() }}'"
                type="button">
                <i aria-hidden="true" class="ki ki-close"></i>
            </button>
        </div>
        <div class="card-body">
            <div class="tab-content">
                <div class="tab-pane fade show active" id="pengajuan" role="tabpanel" aria-labelledby="pengajuan">
                    @include('pengajuan::tad.form.tabs.pengajuan')
                </div>
                {{-- <div class="tab-pane fade" id="tembusan" role="tabpanel" aria-labelledby="tembusan">
                    @include('pengajuan::tad.form.tabs.tembusan')
                </div> --}}
                <div class="tab-pane fade" id="riwayat" role="tabpanel" aria-labelledby="riwayat">
                    @include('pengajuan::tad.form.tabs.riwayat')
                </div>
            </div>
        </div>
        @if (in_array($record->status, [1, 'submit', 'waiting', 'waiting.approval']))
            <div class="card-footer p-5">
                <form action="{{ route($route . '.approvalSave', $record->id) }}" method="POST">
                    @csrf
                    @method('POST')
                    <input type="hidden" name="id" value="{{ $record->id }}">
                    <div class="float-right d-flex flex-row">
                        {{--<x-btn-back class="mr-2" url="{{ route($route . '.index') }}" />
                        <x-btn-draft style="float:right;" via="base-form--submit-page" /> --}}
                        {{-- <x-btn-save via="base-form--submit-page" /> --}}
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
        @elseif(in_array($record->status, ['approved', 'completed']))
            <div class="card-footer p-5">
                <form action="{{ route($route . '.kandidat.send', $record->id) }}" method="POST">
                    @csrf
                    <div class="float-right d-flex flex-row">
                        {{--<x-btn-back class="mr-2" url="{{ url()->previous() }}" />--}}
                        @if (auth()->user()->isVendor && !in_array($record->status, ['approved', 'completed']))
                            <x-btn-save via="base-form--submit-page" label="Kirim" />
                        @endif
                    </div>
                </form>
            </div>
        @elseif($record->status == 'vendor.submit' && auth()->user()->isEhc && false)
            <div class="card-footer p-5">
                <div class="float-right d-flex flex-row">
                    <a href="{{ route('pengajuan.pengajuan.send.interview.mail', $record->id) }}"
                        class="btn btn-info base-modal--confirm"
                        data-confirm-text="<b>Yakin</b> ,mengirim email undangan interview ke Kandidat ?"
                        data-toggle="tooltip" title="Kirim Undangan Interview keseluruh Kandidat TAD via Email ?"><i
                            class="fas fa-at"></i> Kirim Undangan Interview</a>
                </div>
            </div>
        @endif
    </div>
    @if (!in_array($record->status, ['completed', 'approved', 'vendor.submit']))
    @elseif ($record->status == 'verificated.hc')
        <form action="{{ route($route . '.sp.store', $record->id) }}" method="POST" autocomplete="off">
            @csrf
            <div class="card mt-5 card-custom">
                <div class="card-header">
                    <div class="card-title">
                        <span class="card-icon">
                            <i class="far fa-check-circle text-primary"></i>
                        </span>
                        <h3 class="card-label">Otorisasi Human Capital
                            <small>Otorisasi Kandidat</small>
                        </h3>
                    </div>
                </div>
                <div class="card-body">
                    <div class="alert alert-success mb-5 p-5" role="alert">
                        <h4 class="alert-heading">Informasi !</h4>
                        <p>Pastikan kebutuhan kandidat sudah di tindak lanjuti.</p>
                        <p>Lampiran dibawah ini akan dikirimkan ke Unit Kerja & Vendor Terkait.</p>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-3">Nomor Surat</label>
                        <div class="col-6 parent-group">
                            <input type="text" class="form-control" name="no_surat" placeholder="Nomor Surat">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-3">Lampirkan Surat</label>
                        <div class="col-6 parent-group">
                            <div class="custom-file">
                                <input type="file" name="surat" accept=".pdf" class="custom-file-input" />
                                <label class="custom-file-label" for="file" style="color:#B5B5C3;font-weight:400;">Pilih file</label>
                                <span class="form-text text-muted">Lampirkan File Surat Persetujuan dengan format
                                    .pdf</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer p-5">
                    <div class="float-right d-flex flex-row">
                        <x-btn-back class="mr-2" url="{{ route($route . '.index') }}" />
                        <x-btn-save via="base-form--submit-page" />
                    </div>
                </div>
            </div>
        </form>
    @endif
@endsection
{{-- @if (in_array($record->status, ['approved', 'completed', 'vendor.submit']))
    @push('scripts')
        <script>
            $(document).ready(function() {
                $(document)
                    .on('click', '.dropdown-item.base-modal--render', function(){
                        setTimeout(() => {
                            $('[type="checkbox"].kandidat').change();
                        }, 500);
                    })
                    .on('change', '[type="checkbox"].kandidat', function(e) {
                        if ($('[type="checkbox"].kandidat:checked').length == $(this).data('max')) {
                            $('[type="checkbox"].kandidat:not(:checked)')
                                .attr('disabled', 'disabled')
                                .prop('disabled', 'disabled');
                        } else {
                            $('[type="checkbox"].kandidat:not(:checked)')
                                .removeAttr('disabled')
                                .attr('disabled', null)
                                .removeProp('disabled', null)
                                .prop('disabled', null);
                        }
                    });
            });
        </script>
    @endpush
@endif --}}
