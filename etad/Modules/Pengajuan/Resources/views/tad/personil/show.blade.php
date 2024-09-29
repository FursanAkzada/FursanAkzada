{{-- {{ dd(json_decode($record), request()->query()) }} --}}
@extends('layouts.app')
@section('title', $title)
@section('buttons-after') @endsection
@section('content')
    <style>
        .w-lebar {
            width: 90%;
        }
    </style>
    <div class="card card-custom">
        <div class="card-header">
            <div class="card-toolbar justify-content-between w-100">
                <ul class="nav nav-light-danger nav-bold nav-pills">
                    <li class="nav-item">
                        <a class="nav-link @if($activeTab == 'profile') active @endif" data-toggle="tab" href="#profile">
                            <span class="nav-icon" data-toggle="tooltip" title="Profil"><i class="fas fa-user"></i></span>
                            {{-- <span class="nav-text">Profil</span> --}}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#keluarga">
                            <span class="nav-icon" data-toggle="tooltip" title="Keluarga"><i class="fas fa-users"></i></span>
                            {{-- <span class="nav-text">Keluarga</span> --}}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#cv">
                            <span class="nav-icon" data-toggle="tooltip" title="CV"><i class="fas fa-file-contract"></i></span>
                            {{-- <span class="nav-text">CV</span> --}}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#psikotest">
                            <span class="nav-icon" data-toggle="tooltip" title="Psikotest"><i class="fas fa-address-card"></i></span>
                            {{-- <span class="nav-text">Psikotest</span> --}}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#wawancaraHC">
                            <span class="nav-icon" data-toggle="tooltip" title="Wawancara HC"><i class="fas fa-calendar-check"></i></span>
                            {{-- <span class="nav-text">Wawancara HC</span> --}}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link @if($activeTab == 'riwayatKerja') active @endif" data-toggle="tab" href="#riwayatKerja">
                            <span class="nav-icon" data-toggle="tooltip" title="Riwayat Kerja"><i class="fas fa-history"></i></span>
                            {{-- <span class="nav-text">Riwayat Kerja</span> --}}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link @if($activeTab == 'riwayatKepegawaian') active @endif" data-toggle="tab" href="#riwayatKepegawaian">
                            <span class="nav-icon" data-toggle="tooltip" title="Riwayat Kepegawaian"><i class="fas fa-code-branch"></i></span>
                            {{-- <span class="nav-text">Riwayat Kepegawaian</span> --}}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#reward">
                            <span class="nav-icon" data-toggle="tooltip" title="Penghargaan & Pembinaan"><i class="fas fa-medal"></i></span>
                            {{-- <span class="nav-text">Penghargaan & Pembinaan</span> --}}
                        </a>
                    </li>
                </ul>
                @if(isset($kandidat->id) && auth()->user()->isEhc && false)
                    <div class="dropdown dropdown-inline">
                        <button type="button" class="btn btn-hover-light-primary btn-icon btn-sm" data-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
                            <i class="ki ki-bold-more-hor "></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
                            {{-- @if (!is_null($kandidat->wawancara) && $kandidat->wawancara->kesimpulan <= 3) --}}
                            {{-- @else --}}
                            {{-- @endif --}}
                                @php
                                    $param = [
                                        'kandidat'      =>$kandidat->id,
                                        'personil_id'   =>$record->id,
                                        'pengajuan_id'  =>request()->pengajuan_id,
                                        'requirement_id'=>request()->requirement_id,
                                        'kandidat_id'   =>request()->kandidat_id,
                                    ];
                                @endphp
                                @if (empty($kandidat->wawancara))
                                    <a class="dropdown-item base-modal--render"
                                        href="{{ route($route . '.kandidat.wawancara.create', $param) }}"
                                        data-modal-backdrop="false" data-modal-v-middle="false" data-modal-size="modal-xl"
                                        data-toggle="tooltip" data-original-title="Form Wawancara" data-placement="bottom">
                                        <i class="fas fa-tasks text-primary mr-2"></i>Form Wawancara
                                    </a>
                                    <div class="dropdown-divider"></div>
                                @endif
                                @if (is_null($kandidat->wawancara))
                                    <a class="dropdown-item" href="https://wa.me/{{ $record->telepon }}?text=Undangan%20Interview">
                                        <i class="fab fa-whatsapp text-success mr-2"></i>Undang via Whatsapp
                                    </a>
                                    <a class="dropdown-item base-modal--confirm" data-confirm-text="Kirim undangan Interview ?" href="{{ route(str_replace('.personil','.form',$route) . '.send.interview.mail.personal', $record->id) }}">
                                        <i class="fas fa-at text-info mr-2"></i>Undang via Email
                                    </a>
                                @else
                                    @if (is_null($kandidat->accepted))
                                        <a class="dropdown-item base-modal--render"
                                            href="{{ route($route . '.kandidat.penerimaan.create', $param) }}"
                                            data-modal-backdrop="false" data-modal-v-middle="false" data-modal-size="modal-lg"
                                            data-toggle="tooltip" data-original-title="Terima Sebagai Pegawai"
                                            data-placement="bottom">
                                            <i class="fas fa-check-circle text-success mr-2"></i>Penerimaan
                                        </a>
                                        <a class="dropdown-item base-modal--confirm"
                                            href="{{ route($route . '.kandidat.penolakan.store', $param) }}"
                                            data-toggle="tooltip"
                                            data-confirm-text="Yakin <b class='text-danger'>Menolak</b> <b>{{ $kandidat->tad->nama }}</b> Sebagai TAD ?"
                                            {{-- data-submit-class="base-form--submit-confirm" --}} data-original-title="Tolak Sebagai Pegawai"
                                            data-placement="bottom">
                                            <i class="fas fa-ban text-danger mr-2"></i>Penolakan
                                        </a>
                                    @endif
                                @endif
                        </div>
                    </div>
                @endif
                <button aria-label="Close" class="close" data-dismiss="card"
                    onclick="location.href='{{ route($route . '.index') }}'" type="button">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="tab-content">
                <div class="tab-pane fade @if($activeTab == 'profile') show active @endif" id="profile" role="tabpanel" aria-labelledby="profile">
                    @include($view . '.tabs.profile')
                </div>
                <div class="tab-pane fade" id="keluarga" role="tabpanel" aria-labelledby="keluarga">
                    @include($view . '.tabs.keluarga')
                </div>
                <div class="tab-pane fade" id="cv" role="tabpanel" aria-labelledby="cv">
                    @include($view . '.tabs.cv')
                </div>
                <div class="tab-pane fade" id="psikotest" role="tabpanel" aria-labelledby="psikotest">
                    @include($view . '.tabs.psikotest')
                </div>
                <div class="tab-pane fade" id="wawancaraHC" role="tabpanel" aria-labelledby="wawancaraHC">
                    @include($view . '.tabs.wawancara-hc')
                </div>
                <div class="tab-pane fade" id="riwayatKerja" role="tabpanel" aria-labelledby="riwayatKerja">
                    @include($view . '.tabs.riwayat-kerja')
                </div>
                <div class="tab-pane fade" id="riwayatKepegawaian" role="tabpanel" aria-labelledby="riwayatKepegawaian">
                    @include($view . '.tabs.riwayat-kepegawaian')
                </div>
                <div class="tab-pane fade" id="reward" role="tabpanel" aria-labelledby="reward">
                    @include($view . '.tabs.reward')
                </div>
            </div>
        </div>
        <div class="card-footer p-5">
            <div class="float-right d-flex flex-row">
                <x-btn-back class="mr-2" url="{{ route($route . '.index') }}" />
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(".masking-anak").inputmask({
            "mask": "9",
            "repeat": 2,
            "greedy": false
        });
    </script>
@endpush
