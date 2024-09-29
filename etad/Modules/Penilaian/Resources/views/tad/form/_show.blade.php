@extends('layouts.app')
@section('title', $title)
@section('buttons')

@endsection
@section('content')
    <div class="card card-custom">
        <div class="card-header">
            <div class="card-toolbar">
                <ul class="nav nav-light-danger nav-bold nav-pills">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#profile">
                            <span class="nav-icon"><i class="fas fa-user"></i></span>
                            <span class="nav-text">Profil</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#tad">
                            <span class="nav-icon"><i class="fas fa-star"></i></span>
                            <span class="nav-text">Riwayat Penilaian</span>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="card-toolbar">
                <a href="{{ route($route . '.tad.create', $record->id) }}" data-modal-size="modal-xl"
                    class="btn btn-info base-modal--render">
                    <i class="fas fa-plus mr-2"></i> Tambah Penilaian
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="tab-content">
                <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile">
                    @include($view . '.tabs.profile')
                </div>
                <div class="tab-pane fade" id="tad" role="tabpanel" aria-labelledby="tad">
                    @include($view . '.tabs.riwayat')
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script></script>
@endpush
