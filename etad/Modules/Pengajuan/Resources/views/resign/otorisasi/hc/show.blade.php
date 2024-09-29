@extends('layouts.app')

@section('title',$title)

@section('buttons') @endsection

@section('content')
<form action="{{ route($route.'.update', $record->id) }}" method="post">
    @csrf
    @method('PUT')
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
                    @include('pengajuan::resign.otorisasi.hc.tabs.pengajuan')
                </div>
                <div class="tab-pane fade" id="riwayat" role="tabpanel" aria-labelledby="riwayat">
                    @include('pengajuan::resign.otorisasi.hc.tabs.riwayat')
                </div>
            </div>
        </div>
        <div class="card-footer p-5">
            <div class="float-right d-flex flex-row">
                <x-btn-back class="mr-2" url="{{ route($route.'.index') }}" />
                @if($record->active_logs->status != 'approved.hc')
                <x-btn-save via="base-form--submit-page" />
                @endif
            </div>
        </div>
    </div>
</form>
@endsection
