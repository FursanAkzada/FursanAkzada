@extends('layouts.app')
@section('title', $title)
@section('buttons-after') @endsection
@section('content')
    <form action="{{ route($route . '.update', $record->id) }}" method="POST">
        @csrf
        @method('PATCH')
        <input type="hidden" name="penerimaan_id" value="{{ $record->id }}">
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
                                <input class="form-control" placeholder="{{ __('ID Pengajuan') }}"
                                    value="{{ $record->wawancara->kandidat->summary->pengajuan->no_tiket }}" disabled>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label">{{ __('Unit Kerja') }}</label>
                            <div class="col-md-8 parent-group">
                                <input class="form-control" placeholder="{{ __('Unit Kerja') }}"
                                    value="{{ $record->wawancara->kandidat->summary->pengajuan->so->name ?? '' }}" disabled>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label">{{ __('Posisi TAD') }}</label>
                            <div class="col-md-8 parent-group">
                                <input class="form-control" placeholder="{{ __('Posisi TAD') }}"
                                    value="{{ $record->wawancara->kandidat->summary->requirement->jabatan->NM_UNIT . ' ( ' . $record->wawancara->kandidat->summary->requirement->jumlah . ' posisi ' . ')' }}"
                                    disabled>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label">{{ __('Nama Kandidat') }}</label>
                            <div class="col-md-8 parent-group">
                                <input class="form-control" placeholder="{{ __('Nama Kandidat') }}"
                                    value="{{ $record->wawancara->kandidat->tad->nama }}" disabled>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label">{{ __('Tgl Pengajuan') }}</label>
                            <div class="col-md-8 parent-group">
                                <input class="form-control" placeholder="{{ __('Tgl Pengajuan') }}"
                                    value="{{ $record->wawancara->kandidat->summary->pengajuan->tgl_pengajuan->translatedFormat('d/m/Y') }}"
                                    disabled>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label">{{ __('Periode') }}</label>
                            <div class="col-md-8 parent-group">
                                <input class="form-control" placeholder="{{ __('Periode') }}"
                                    value="{{ 'Tahun ' . $record->wawancara->kandidat->summary->pengajuan->year . ' Semester ' . $record->wawancara->kandidat->summary->pengajuan->semester }}"
                                    disabled>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label">{{ __('Vendor') }}</label>
                            <div class="col-md-8 parent-group">
                                <input class="form-control" placeholder="{{ __('Vendor') }}"
                                    value="{{ $record->wawancara->kandidat->summary->requirement->vendor->nama }}"
                                    disabled>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label">{{ __('Skor Wawancara') }}</label>
                            <div class="col-md-8 parent-group">
                                <input class="form-control" placeholder="{{ __('Skor Wawancara') }}"
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
                                        <select id="isKeputusanCtrl" class="form-control base-plugin--select2 show-tick"
                                            name="keputusan" data-placeholder="Diterima/Ditolak">
                                            <!-- <option value="">Diterima/Ditolak</option> -->
                                            <option value="Diterima" @if ($record->keputusan == 'Diterima') selected @endif>
                                                Diterima</option>
                                            <option value="Ditolak" @if ($record->keputusan == 'Ditolak') selected @endif>
                                                Ditolak</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-md-4 col-form-label">{{ __('Tgl Keputusan') }}</label>
                                    <div class="col-md-8 parent-group">
                                        <input type="hidden" name="tanggal_sekarang" value="{{ now()->format('d/m/Y') }}">
                                        <input name="tgl_keputusan" id="tglKeputusanCtrl"
                                            class="form-control base-plugin--datepicker tgl-pengajuan"
                                            data-format="dd/mm/yyyy" data-options='@json(['endDate' => now()->format('d/m/Y'), 'format' => 'dd/mm/yyyy'])'
                                            placeholder="{{ __('Tgl Keputusan') }}" @if ($record->tgl_keputusan)
                                        value="{{ $record->tgl_keputusan->format('d/m/Y') }}"
                                        @endif>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-md-4 col-form-label">{{ __('Tgl Mulai Kontrak') }}</label>
                                    <div class="col-md-8 parent-group">
                                        <input id="tglKontrakStartCtrl" name="start_date_contract"
                                            class="form-control base-plugin--datepicker start-date-contract"
                                            data-format="dd/mm/yyyy" data-options='@json(['startDate' => now(), 'format' => 'dd/mm/yyyy'])'
                                            placeholder="{{ __('Tgl Mulai Kontrak') }}" @if ($record->start_date_contract)
                                        value="{{ $record->start_date_contract->format('d/m/Y') }}"
                                        @endif @if ($record->keputusan == 'Ditolak')
                                            disabled
                                        @endif>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-md-4 col-form-label">{{ __('Tgl Selesai Kontrak') }}</label>
                                    <div class="col-md-8 parent-group">
                                        <input id="tglKontrakEndCtrl" name="tgl_contractdue"
                                            class="form-control base-plugin--datepicker tgl-contract-due"
                                            data-format="dd/mm/yyyy" data-options='@json(['startDate' => now(), 'format' => 'dd/mm/yyyy'])'
                                            placeholder="{{ __('Tgl Selesai Kontrak') }}" @if ($record->tgl_contractdue)
                                        value="{{ $record->tgl_contractdue->format('d/m/Y') }}"
                                        @endif @if ($record->keputusan == 'Ditolak')
                                            disabled
                                        @endif>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-md-4 col-form-label">{{ __('NIO') }}</label>
                                    <div class="col-md-8 parent-group">
                                        <input id="nioCtrl" class="form-control masking-nio" name="nio"
                                            placeholder="NIO" value="{{ $record->nio }}" maxlength="8"
                                            @if ($record->keputusan == 'Ditolak') disabled @endif>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-md-4 col-form-label">{{ __('No SK') }}</label>
                                    <div class="col-md-8 parent-group">
                                        <input id="noSkCtrl" class="form-control" name="no_sk" placeholder="No SK"
                                            value="{{ $record->no_sk }}" maxlength="32"
                                            @if ($record->keputusan == 'Ditolak') disabled @endif>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label for="" class="col-2 col-form-label">Kalimat Pembuka</label>
                                    <div class="col-10 parent-group">
                                        <textarea name="pembukaan" class="base-plugin--summernote-2" data-height="200"
                                            placeholder="{{ __('Kalimat Pembuka') }}">
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
                                        <textarea name="isi_surat" class="base-plugin--summernote-2" data-height="200" placeholder="{{ __('Isi Surat') }}">
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
                                            placeholder="{{ __('Kalimat Penutup') }}">
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
                                        <div class="custom-file">
                                            <input type="hidden" name="uploads[uploaded]" class="uploaded"
                                                value="">
                                            <input type="file" multiple data-name="uploads"
                                                class="custom-file-input base-form--save-temp-files"
                                                data-container="parent-group" data-max-size="2048" data-max-file="100"
                                                accept="*">
                                            <label class="custom-file-label" for="file" style="color:#B5B5C3;font-weight:400;">{{ 'Pilih file' }}</label>
                                        </div>
                                        <div class="form-text text-muted">*Maksimal 2MB</div>
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
                                                    <div class="alert-close">
                                                        <button type="button" class="close base-form--remove-temp-files"
                                                            data-toggle="tooltip" data-original-title="Remove">
                                                            <span aria-hidden="true">
                                                                <i class="ki ki-close"></i>
                                                            </span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            @php
                $tipe = $module;
            @endphp
            <div class="col-6">
                @include('pengajuan::tad.penerimaan.flow')
            </div>
            <div class="col-6">
                <div class="card card-custom gutter-b" style="margin-bottom:0; height:100%;">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between flex-lg-wrap flex-xl-nowrap p-4">
                            <div class="d-flex flex-column mr-5">
                                <span class="h4 text-dark text-hover-primary mb-5">
                                    Informasi
                                </span>
                                <p class="text-dark-50">
                                    Sebelum submit pastikan data Penerimaan TAD tersebut sudah sesuai.
                                </p>
                            </div>
                            <div class="ml-lg-0 ml-xxl-6 ml-6 flex-shrink-0">
                                @php
                                    $menu = \Modules\Settings\Entities\Menu::where('code', $tipe)->first();
                                    $count = $menu->flows()->count();
                                    $submit = $count == 0 ? 'disabled' : 'enabled';
                                @endphp
                                <div style="display: none">
                                    <x-btn-back class="mr-2" url="{{ route($route . '.index') }}" />
                                </div>
                                <input type="hidden" name="is_submit" value="1">

                                <x-btn-draft via="base-form--submit-page" confirm="true" submit="{{ $submit }}" />
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
        $(".masking-nio").inputmask({
            "mask": "9",
            "repeat": 30,
            "greedy": false
        });
        $('#isKeputusanCtrl').on('change', function() {
            if (this.value == "Ditolak") {
                $('#tglKontrakEndCtrl').val('');
                $("#tglKontrakEndCtrl").prop("disabled", true);

                $('#tglKontrakStartCtrl').val('');
                $("#tglKontrakStartCtrl").prop("disabled", true);

                $('#nioCtrl').val('');
                $("#nioCtrl").prop("disabled", true);

                $('#noSkCtrl').val('');
                $("#noSkCtrl").prop("disabled", true);
            } else {
                $("#tglKontrakEndCtrl").prop("disabled", false);
                $("#tglKontrakStartCtrl").prop("disabled", false);

                $("#nioCtrl").prop("disabled", false);
                $("#noSkCtrl").prop("disabled", false);
            }
        });
    </script>
@endpush
