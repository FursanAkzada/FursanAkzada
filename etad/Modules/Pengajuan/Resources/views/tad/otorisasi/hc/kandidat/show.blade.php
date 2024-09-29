@extends('layouts.app')
@section('title', $title)
@section('buttons') @endsection
@section('content')
    <div class="card card-custom">
        <div class="card-header card-header-right ribbon ribbon-left">
            {!! $kandidat->ribbonRaw() !!}
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
                        <a class="nav-link" data-toggle="tab" href="#similar">
                            <span class="nav-icon"><i class="fas fa-users"></i></span>
                            <span class="nav-text">Similar</span>
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
            <div class="dropdown dropdown-inline">
                <button type="button" class="btn btn-hover-light-primary btn-icon btn-sm" data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">
                    <i class="ki ki-bold-more-hor "></i>
                </button>
                <div class="dropdown-menu dropdown-menu-right">
                    {{-- @if (!is_null($kandidat->wawancara) && $kandidat->wawancara->kesimpulan <= 3) --}}
                    {{-- @else --}}
                    {{-- @endif --}}
                    @if (empty($kandidat->wawancara))
                        <a class="dropdown-item base-modal--render"
                            href="{{ route($route . '.kandidat.wawancara.create', $kandidat->id) }}"
                            data-modal-backdrop="false" data-modal-v-middle="false" data-modal-size="modal-xl"
                            data-toggle="tooltip" data-original-title="Form Wawancara" data-placement="bottom"><i
                                class="fas fa-tasks text-primary mr-2"></i>Form Wawancara</a>
                        <div class="dropdown-divider"></div>
                    @endif

                    <a class="dropdown-item" href="#">
                        <i class="fab fa-whatsapp text-success mr-2"></i>Undang via Whatsapp
                    </a>
                    <a class="dropdown-item" href="#">
                        <i class="fas fa-at text-info mr-2"></i>Undang via Email
                    </a>
                    @if (!is_null($kandidat->wawancara))
                        @if (is_null($kandidat->accepted))
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item base-modal--render"
                                href="{{ route($route . '.kandidat.penerimaan.create', $kandidat->id) }}"
                                data-modal-backdrop="false" data-modal-v-middle="false" data-modal-size="modal-lg"
                                data-toggle="tooltip" data-original-title="Terima Sebagai Pegawai"
                                data-placement="bottom"><i
                                    class="fas fa-check-circle text-success mr-2"></i>Penerimaan</a>
                            <a class="dropdown-item base-modal--confirm"
                                href="{{ route($route . '.kandidat.penolakan.store', $kandidat->id) }}"
                                data-toggle="tooltip"
                                data-confirm-text="Yakin <b class='text-danger'>Menolak</b> <b>{{ $kandidat->tad->nama }}</b> Sebagai TAD ?"
                                {{-- data-submit-class="base-form--submit-confirm" --}} data-original-title="Tolak Sebagai Pegawai"
                                data-placement="bottom"><i class="fas fa-ban text-danger mr-2"></i>Penolakan</a>
                        @endif
                    @endif
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="tab-content">
                <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile">
                    @include($view . '.kandidat.tabs.profile')
                </div>
                <div class="tab-pane fade" id="keluarga" role="tabpanel" aria-labelledby="keluarga">
                    @include($view . '.kandidat.tabs.keluarga')
                </div>
                <div class="tab-pane fade" id="similar" role="tabpanel" aria-labelledby="similar">
                    @include($view . '.kandidat.tabs.similar')
                </div>
                <div class="tab-pane fade" id="cv" role="tabpanel" aria-labelledby="cv">
                    @include($view . '.kandidat.tabs.cv')
                </div>
                <div class="tab-pane fade" id="psikotest" role="tabpanel" aria-labelledby="psikotest">
                    @include($view . '.kandidat.tabs.psikotest')
                </div>
                <div class="tab-pane fade" id="wawancara" role="tabpanel" aria-labelledby="wawancara">
                    @include($view . '.kandidat.tabs.wawancara')
                </div>
            </div>
        </div>
        <div class="card-footer p-5">
            <div class="float-right d-flex flex-row">
                <x-btn-back class="mr-2" url="{{ route($route . '.show', $pengajuan->id) }}" />
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script></script>
@endpush
