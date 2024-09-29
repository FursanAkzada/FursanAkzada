@extends('layouts.app')
@section('title',$title)
@section('buttons') @endsection
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
          <a class="nav-link" data-toggle="tab" href="#keluarga">
            <span class="nav-icon"><i class="fas fa-users"></i></span>
            <span class="nav-text">Keluarga</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" data-toggle="tab" href="#cv">
            <span class="nav-icon"><i class="fas fa-file-contract"></i></span>
            <span class="nav-text">CV</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" data-toggle="tab" href="#psikotest">
            <span class="nav-icon"><i class="fas fa-file-contract"></i></span>
            <span class="nav-text">Psikotest</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" data-toggle="tab" href="#wawancara">
            <span class="nav-icon"><i class="fas fa-calendar-check"></i></span>
            <span class="nav-text">Riwayat Wawancara</span>
          </a>
        </li>
      </ul>
    </div>
  </div>
  <div class="card-body">
    <div class="tab-content">
      <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile">
        @include($view.'.kandidat.tabs.profile')
      </div>
      <div class="tab-pane fade" id="keluarga" role="tabpanel" aria-labelledby="keluarga">
        @include($view.'.kandidat.tabs.keluarga')
      </div>
      <div class="tab-pane fade" id="cv" role="tabpanel" aria-labelledby="cv">
        @include($view.'.kandidat.tabs.cv')
      </div>
      <div class="tab-pane fade" id="psikotest" role="tabpanel" aria-labelledby="psikotest">
        @include($view.'.kandidat.tabs.psikotest')
      </div>
      <div class="tab-pane fade" id="wawancara" role="tabpanel" aria-labelledby="wawancara">
        @include($view.'.kandidat.tabs.wawancara')
      </div>
    </div>
  </div>
  <div class="card-footer p-5">
    <div class="float-right d-flex flex-row">
      {{-- <x-btn-back class="mr-2" url="{{ route($route.'.show',$pengajuan->id) }}" /> --}}
    </div>
  </div>
</div>
@endsection
@push('scripts')
  <script>

  </script>
@endpush