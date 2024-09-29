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
    <form action="{{ route($route . '.update', $record->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card card-custom">
            <div class="card-header">
                <h4 class="card-title">Ubah {{ $title }}</h4>
                <button aria-label="Close" class="close" data-dismiss="modal"
                    onclick="location.href='{{ url()->previous() }}'" type="button">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="card-body">
                <div class="form-group row">
                    <label for="" class="col-3 text-bold">Nama</label>
                    <div class="col">
                        <select class="filter-control form-control base-plugin--select2" id="personilCtrl" name="tad_id"
                            title="Pilih Personil TAD">
                            <option selected disabled></option>
                            @foreach ($POSISI_TAD as $posisi_tad)
                                <optgroup label="{{ $posisi_tad->NM_UNIT }}">
                                    @foreach ($posisi_tad->personils as $personil)
                                        <option value="{{ $personil->id }}" data-jabatan="{{ $posisi_tad->NM_UNIT }}"
                                            data-nio="{{ $personil->kepegawaian->nio }}"
                                            data-unit-kerja="{{ $personil->kepegawaian->unitKerja->name }}"
                                            @if ($record->tad_id == $personil->id) selected @endif>
                                            {{ $personil->nama }}
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-3 text-bold">NIO</label>
                    <label class="col" id="nioValue"></label>
                </div>
                <div class="form-group row">
                    <label class="col-3 text-bold">Posisi TAD</label>
                    <label class="col" id="jabatanValue"></label>
                </div>
                <div class="form-group row">
                    <label class="col-3 text-bold">Unit Kerja</label>
                    <label class="col" id="unitKerjaValue"></label>
                </div>
                <div class="form-group row">
                    <label class="col-3 text-bold">SK</label>
                    <div class="col parent-group">
                        <input class="form-control" name="sk" placeholder="SK" value="{{ $record->sk }}">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="" class="col-3 text-bold">Tanggal SK</label>
                    <div class="col parent-group">
                        <input name="tanggal_sk" class="form-control base-plugin--datepicker-1" data-language="en"
                            data-format="dd/mm/yyyy"
                            placeholder="Pilih Tanggal SK" value="{{ $record->tanggal_sk->format('d/m/Y') }}">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="" class="col-3 text-bold">Jenis Pembinaan</label>
                    <div class="col parent-group">
                        <select class="form-control base-plugin--select2" name="jenis_id" title="Pilih Salah Satu">
                            <option></option>
                            @foreach (\App\Entities\EHC\JenisPunishment::punishment()->get() as $item)
                                <option value="{{ $item->sandi }}"
                                    @if($record->jenis_id == $item->sandi) selected @endif>
                                    {{ $item->Lengkap }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="" class="col-3 text-bold">Eviden</label>
                    <div class="col parent-group">
                        <textarea class="form-control" name="eviden" placeholder="Eviden">{{ $record->eviden }}</textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="" class="col-3 text-bold">Tanggal Mulai</label>
                    <div class="col parent-group">
                        <input class="form-control base-plugin--datepicker-1" data-language="en" name="tanggal_mulai"
                            data-format="dd/mm/yyyy"
                            placeholder="Pilih Tanggal Mulai" value="{{ $record->tanggal_mulai->format('d/m/Y') }}">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="" class="col-3 text-bold">Tanggal Selesai</label>
                    <div class="col parent-group">
                        <input class="form-control base-plugin--datepicker-1" data-language="en"
                            data-format="dd/mm/yyyy"
                            name="tanggal_selesai" placeholder="Pilih Tanggal Selesai" value="{{ $record->tanggal_selesai->format('d/m/Y') }}">
                    </div>
                </div>
            </div>
        </div>
        <br>
        <div class="row">
            @php
                $tipe = 'pu.punishment';
            @endphp
            <div class="col-6">
                @include('penilaian::tad.form.flow')
            </div>
            <div class="col">
                <div class="card card-custom gutter-b">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between p-4 flex-lg-wrap flex-xl-nowrap">
                            <div class="d-flex flex-column mr-5">
                                <a href="#" class="h4 text-dark text-hover-primary mb-5">
                                    Informasi
                                </a>
                                <p class="text-dark-50">
                                    Sebelum submit pastikan data Reward tersebut sudah sesuai.
                                </p>
                            </div>
                            <div class="ml-6 ml-lg-0 ml-xxl-6 flex-shrink-0">
                                @php
                                    $menu = \Modules\Settings\Entities\Menu::where('code', $tipe)->first();
                                    $count = $menu->flows()->count();
                                    $submit = $count == 0 ? 'disabled' : 'enabled';
                                @endphp
                                <div style="display: none">
                                    <x-btn-back class="mr-2" url="{{ route($route . '.index') }}" />
                                </div>
                                <x-btn-draft via="base-form--submit-page" submit="{{ $submit }}" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            $(document).on('change', '#personilCtrl', function() {
                let data = $(this).find('option:selected').data();
                console.log(270, data);
                $('#nioValue').text(data.nio);
                $('#jabatanValue').text(data.jabatan);
                $('#unitKerjaValue').text(data.unitKerja);
                $('#masaKerjaCtrl').val(data.masaKerja);
            });
            $('#personilCtrl').change();
        });
    </script>
@endpush
