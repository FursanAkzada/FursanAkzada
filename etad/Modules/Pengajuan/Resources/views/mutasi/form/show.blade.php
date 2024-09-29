@php
    $user = auth()->user();
    $soLevelMap = [''];
    $so_id = $user->position->org_struct_id ?? null;
@endphp
@extends('layouts.app')
@section('title', $title)
@section('buttons-after') @endsection
@push('styles')
    <style>
        .select2-selection__rendered#select2-unitKerjaAsalCtrl-container {
            color: #3F4254 !important;
        }
    </style>
@endpush
@section('content')
    <div class="card card-custom">
        <div class="card-header">
            <h5 class="card-title">
                {!! $title !!}
            </h5>
            <button aria-label="Close" class="close" data-dismiss="card" onclick="location.href='{{ url()->previous() }}'"
                type="button">
                <i aria-hidden="true" class="ki ki-close"></i>
            </button>
        </div>
        <div class="card-body">
            <div class="tab-content">
                <div class="row">
                    <div class="col-6">
                        <div class="form-group row">
                            <label for="" class="col-4 text-bold">Unit Kerja Asal</label>
                            <div class="col-8 parent-group">
                                <input class="form-control" disabled value="{{$record->unitKerjaAsal->name}}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-4 text-bold">Unit Kerja Tujuan</label>
                            <div class="col-8 parent-group">
                                <input class="form-control" disabled value="{{$record->unitKerjaTujuan->name}}">
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group row">
                            <label for="" class="col-4 text-bold">Tgl Pengajuan</label>
                            <div class="col-8">
                                <input class="form-control" disabled value="{{$record->tgl_pengajuan->format('d/m/Y') }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-4 text-bold">No. Surat</label>
                            <div class="col-8">
                                <input class="form-control" disabled value="{{$record->no_tiket}}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="form-group row">
                            <label for="" class="col-2 col-form-label text-bold">Perihal</label>
                            <div class="col-10 parent-group">
                                <input type="text" name="perihal" class="form-control" placeholder="{{ __('Perihal') }}"
                                    value="{{ $record->perihal }}" disabled>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="" class="col-2 col-form-label text-bold">Kepada</label>
                    <div class="col-10 parent-group">
                        <select name="to[]" id="" class="form-control base-plugin--select2-ajax"
                            title="Pilih Kepada" data-url="{{ route('settings.user.ajax.selectAjaxUserDivisiHC') }}" disabled>
                            @foreach ($record->to as $to)
                                <option selected value="{{ $to->id }}">
                                    {{ $to->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="" class="col-2 text-bold">Surat Permohonan</label>
                    <div class="col-10 parent-group">
                        @foreach ($record->files as $file)
                        <div class="progress-container w-100" data-uid="{{ $file->id }}">
                            <div class="alert alert-custom alert-light fade show py-2 px-3 mb-0 mt-2 success-uploaded" role="alert">
                                <div class="alert-icon">
                                    <i class="{{ $file->file_icon }}"></i>
                                </div>
                                <div class="alert-text text-left">
                                    <input type="hidden" name="uploads[files_ids][]" value="{{ $file->id }}">
                                    <div>Uploaded File:</div>
                                    <a href="{{ $file->file_url }}" target="_blank" class="text-primary">
                                        {{ $file->file_name }}
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="form-group row">
                    <label for="" class="col-2 text-bold">Tembusan</label>
                    <div class="col-10 parent-group">
                        <select name="user_id[]" id="" multiple class="form-control base-plugin--select2-ajax"
                            title="Pilih User" data-url="{{ route('master.so.jabatan.select-cc') }}" disabled>
                            <option value=""></option>
                            @foreach ($record->cc as $item)
                                <option value="{{ $item->id }}" selected>{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="" class="col-2 col-form-label text-bold">Menunjuk</label>
                    <div class="col-10 parent-group">
                        <textarea name="menunjuk" class="base-plugin--summernote-2" data-height="200"
                            placeholder="{{ __('Menunjuk') }}" disabled>{!! $record->menunjuk !!}</textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="" class="col-2 col-form-label text-bold">Menindaklanjuti</label>
                    <div class="col-10 parent-group">
                        <textarea name="menindaklanjuti" class="form-control base-plugin--summernote-2" data-height="200"
                            placeholder="{{ __('Menindaklanjuti') }}" disabled>{!! $record->menindaklanjuti !!}</textarea>
                    </div>
                </div>
                <hr class="my-8">
                <div id="requirement-row">
                    <table class="table-bordered table">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 2em">No</th>
                                <th class="text-center">Posisi TAD</th>
                                <th class="text-center">NIO</th>
                                <th class="text-center">Vendor</th>
                                <th class="text-center">Personil TAD</th>
                                <th class="text-center">Tgl SK Mutasi</th>
                                <th class="text-center">Tgl Efektif</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($record->pegawai as $pegawai)
                                <tr>
                                    <td>{{ $loop->iteration }}.</td>
                                    <td>{{ $pegawai->jabatan->NM_UNIT }}</td>
                                    <td class="text-center">{{ $pegawai->kepegawaian->nio ?? '-'}}</td>
                                    <td class="text-center">{{ $pegawai->vendor->nama }}</td>
                                    <td>{{ $pegawai->nama }}</td>
                                    <td class="text-center">{{ date('d/m/Y', strtotime($pegawai->pivot->tgl_mutasi)) }}
                                    <td class="text-center">{{ date('d/m/Y', strtotime($pegawai->pivot->tgl_efektif)) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="card-footer p-5">
            <form action="{{ route($route . '.approvalSave', $record->id) }}" method="POST">
                @csrf
                @method('POST')
                <input type="hidden" name="id" value="{{ $record->id }}">
                <div class="float-right d-flex flex-row">
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
    </div>
@endsection

@push('scripts')
    <script>
        addRow = (elem) => {
            var row = $('#requirement-form').html();
            var index = $('.form-row:last').index();
            var tmpl = jsrender.templates(row);
            var resp = {
                'index': index + 1
            };
            var dom = tmpl.render(resp);
            $('#requirement-row').append(dom);
            console.log(resp);

            BasePlugin.initSelect2();
            BasePlugin.initDatepicker();
        }
        deleteRow = (elem) => {
            $(elem).parent().parent().remove();
            BasePlugin.initSelect2();
        }

        function countRow() {
            return $('.form-row:last').index() + 1;
        }

        function selectNio(nio) {
            var totalNioSelected = $('select.personil-ctrl option:selected[value="' + nio.value + '"]').length;
            if (nio.value != '' && totalNioSelected > 1) {
                $.gritter.add({
                    title: 'Failed!',
                    text: 'Personil sudah di pilih!',
                    image: baseurl + '/assets/images/icon/ui/cross.png',
                    sticky: false,
                    time: '3000'
                });
                console.log($(nio).val());
                $(nio).val('').trigger('change');
            }
        }

        function unitKerjaAsalChanged(exists = false) {
            if (exists) {
                $('select.jabatan-ctrl').prop('disabled', false);
                $('select.personil-ctrl').prop('disabled', false);
                $('.tgl-mutasi-ctrl').prop('disabled', false);
                $('.btn-add-requirement').prop('disabled', false);
            } else {
                $('select.jabatan-ctrl').prop('disabled', true);
                $('select.personil-ctrl').prop('disabled', true);
                $('.tgl-mutasi-ctrl').prop('disabled', true);
                $('.btn-add-requirement').prop('disabled', true);
            }
        }

        let unitKerjaAsal;
        let unitKerjaAsalText;
        $(document).ready(function() {
            $(document)
                .on('change', '#unitKerjaAsalCtrl', function() {
                    let unitKerjaAsalCtrl = $('#unitKerjaAsalCtrl');
                    unitKerjaAsal = unitKerjaAsalCtrl.val();
                    unitKerjaAsalText = unitKerjaAsalCtrl.find('option:selected').text();
                    unitKerjaAsalChanged(Boolean(unitKerjaAsal));
                    $('#jabatan0Ctrl, .jabatan-ctrl').val('').change();
                    $('#personil0Ctrl, .personil-ctrl').val('').change();
                    $('#tglMutasi0Ctrl, .tgl-mutasi-ctrl').val('').change();
                })
                .on('change', '.jabatan-ctrl', function() {
                    let jabatan_id = $(this).val();
                    let personilCtrl = $(this).parent().parent().find('.personil-ctrl');
                    console.log(176, personilCtrl);
                    $.ajax({
                        method: 'GET',
                        url: '{{ url('pengajuan/tad/personil/ajax') }}',
                        data: {
                            jabatan_id: jabatan_id,
                            so_id: unitKerjaAsal,
                            form_type: 'mutasi',
                        },
                        success: function(response, state, xhr) {
                            let options = `<option value='' selected disabled></option>`;
                            // let options = ``;
                            for (let item of response) {
                                options += `<option value='${item.id}'>${item.nama}</option>`;
                            }
                            personilCtrl.select2('destroy');
                            personilCtrl.html(options);
                            personilCtrl.select2();
                        },
                        error: function(a, b, c) {
                            console.log(a, b, c);
                        }
                    });
                });
        });
    </script>
@endpush
