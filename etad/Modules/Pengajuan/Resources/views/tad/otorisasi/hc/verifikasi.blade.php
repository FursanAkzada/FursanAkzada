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
        </div>
        <div class="card-body">
            <div class="tab-content">
                <div class="tab-pane fade show active" id="pengajuan" role="tabpanel" aria-labelledby="pengajuan">
                    @include('pengajuan::tad.otorisasi.hc.tabs.pengajuan')
                </div>
                <div class="tab-pane fade" id="riwayat" role="tabpanel" aria-labelledby="riwayat">
                    @include('pengajuan::tad.otorisasi.hc.tabs.riwayat')
                </div>
            </div>
        </div>
        <div class="card-footer p-5">
            <div class="float-right d-flex flex-row">
                <a href="{{ route('pengajuan.pengajuan.send.interview.mail', $record->id) }}" class="btn btn-info base-modal--confirm"
                    data-confirm-text="<b>Yakin</b> ,mengirim email undangan interview ke Kandidat ?" data-toggle="tooltip"
                    title="Kirim Undangan Interview keseluruh Kandidat TAD via Email ?"><i class="fas fa-at"></i> Kirim Undangan Interview</a>
            </div>
        </div>
    </div>

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
                    <p>Lampiran dibawah ini akan dikirimkan ke Cabang / Divisi & Vendor Terkait.</p>
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
                            <span class="form-text text-muted">Lampirkan File Surat Persetujuan dengan format .pdf</span>
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
@endsection
