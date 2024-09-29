@extends('layouts.app')
@section('title', $title)
@section('buttons-after') @endsection
@section('content')
<form action="{{ route($route . '.update', $record->id) }}" method="POST">
    @csrf
    @method('PATCH')
    <div class="card card-custom">
        <div class="card-header">
            <h5 class="card-title">
                Detail Wawancara
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
                            <input class="form-control" type="text" placeholder="{{ __('ID Pengajuan') }}"
                                value="{{$record->kandidat->summary->pengajuan->no_tiket}}" disabled>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label">{{ __('Unit Kerja') }}</label>
                        <div class="col-md-8 parent-group">
                            <input class="form-control" type="text" placeholder="{{ __('Unit Kerja') }}"
                                value="{{ $record->kandidat->summary->pengajuan->so->name ?? '' }}" disabled>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label">{{ __('Posisi TAD') }}</label>
                        <div class="col-md-8 parent-group">
                            <input class="form-control" type="text" placeholder="{{ __('Posisi TAD') }}"
                                value="{{ $record->kandidat->summary->requirement->jabatan->NM_UNIT . ' ( ' . $record->kandidat->summary->requirement->jumlah . ' posisi ' . ')'}}" disabled>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label">{{ __('Nama Kandidat') }}</label>
                        <div class="col-md-8 parent-group">
                            <input class="form-control" type="text" placeholder="{{ __('Nama Kandidat') }}"
                                value="{{ $record->kandidat->tad->nama }}" disabled>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label">{{ __('Tgl Pengajuan') }}</label>
                        <div class="col-md-8 parent-group">
                            <input class="form-control" type="text" placeholder="{{ __('Tgl Pengajuan') }}"
                                value="{{ $record->kandidat->summary->pengajuan->tgl_pengajuan->translatedFormat('d/m/Y') }}" disabled>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label">{{ __('Periode') }}</label>
                        <div class="col-md-8 parent-group">
                            <input class="form-control" type="text" placeholder="{{ __('Periode') }}"
                                value="{{ 'Tahun ' . $record->kandidat->summary->pengajuan->year . ' Semester ' . $record->kandidat->summary->pengajuan->semester }}" disabled>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label">{{ __('Vendor') }}</label>
                        <div class="col-md-8 parent-group">
                            <input class="form-control" type="text" placeholder="{{ __('Vendor') }}" value="{{$record->kandidat->summary->requirement->vendor->nama}}" disabled>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-md-4 col-form-label">Tgl Wawancara</label>
                        <div class="col-md-8 parent-group">
                            <input type="text"  name="tgl_wawancara"
                                    class="form-control base-plugin--datepicker tgl-wawancara" data-orientation="bottom"
                                    data-options='@json(['endDate' => now()->format('d/m/Y'), 'format' => 'dd/mm/yyyy'])'
                                    placeholder="{{ __('Tgl Wawancara') }}" @if($record->tgl_wawancara) value="{{ $record->tgl_wawancara->format('d/m/Y') }}" @endif>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">{{ __('Curiculum Vitae') }}</label>
                        <div class="col-md-10 parent-group">
                            @foreach ($record->kandidat->tad->files->where('flag', 'cv') as $file)
                            <div class="progress-container w-100" data-uid="{{ $file->id }}">
                                <div class="alert alert-custom alert-light fade show py-2 px-3 mb-0 mt-2 success-uploaded" role="alert">
                                    <div class="alert-icon">
                                        <i class="{{ $file->file_icon }}"></i>
                                    </div>
                                    <div class="alert-text text-left">
                                        <input type="hidden" name="uploads_cv[files_ids][]" value="{{ $file->id }}">
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
                        <label class="col-md-2 col-form-label">{{ __('Pewawancara') }}</label>
                        <div class="col-md-10 parent-group">
                            <select name="pewawancaras[]" id="" multiple class="form-control base-plugin--select2-ajax"
                                title="Pilih Pewawancara" data-url="{{ route('settings.user.ajax.selectAjaxUserDivisiHC', ['unit_kerja_id' => $record->kandidat->summary->pengajuan->so->id]) }}">
                                @foreach ($record->pewawancaras as $item)
                                    <option value="{{ $item->user->id }}" selected>{{ $item->user->name }} ({{ $item->user->position_name }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <hr class="my-8">
            <div class="form-group row">
                <div class="col-md-12 col-form-label">
                    <div>{{ __('Keterangan Nilai (Memberikan Centang Pada Lingkaran Angka Nilai)') }}</div>
                </div>
                <div class="col-md-12 parent-group">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th class="text-center width-60px">NO</th>
                                    <th class="text-center">PERTANYAAN</th>
                                    <th class="text-center width-60px">1</th>
                                    <th class="text-center width-60px">2</th>
                                    <th class="text-center width-60px">3</th>
                                    <th class="text-center width-60px">4</th>
                                    <th class="text-center width-60px">5</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($kompetensi as $category)
                                    @php
                                        $n =0;
                                    @endphp
                                    <tr>
                                        <td class="text-center width-60px text-bold">{{ $loop->iteration }}</td>
                                        <td class="text-left text-bold">{{ $category->kompetensi }}</td>
                                        <td class="text-center width-60px valign-middle parent-group"></td>
                                        <td class="text-center width-60px valign-middle parent-group"></td>
                                        <td class="text-center width-60px valign-middle parent-group"></td>
                                        <td class="text-center width-60px valign-middle parent-group"></td>
                                    </tr>
                                    @foreach ($record->details as $dd)
                                        @php
                                            $answer = 0;
                                            if ($detail = $record->details()->where('id', $dd->id)->first()) {
                                                $answer = $detail->value;
                                            }
                                        @endphp
                                        @if($dd->pertanyaan->kompetensi_id == $category->id)
                                        @php
                                            $n = $n + 1;
                                        @endphp
                                        <tr>
                                            <td class="text-center width-60px">
                                                <div class="text-nowrap">{{ $loop->parent->iteration.'.'.$n }}</div>
                                            </td>
                                            <td class="text-left pl-40px">
                                                <input type="hidden" name="details[{{ $dd->pertanyaan_id }}][pertanyaan_id]" value="{{ $dd->pertanyaan_id }}">
                                                {!! $dd->pertanyaan->pertanyaan !!}
                                            </td>
                                            <td class="text-center width-60px valign-middle parent-group">
                                                <div class="d-inline-block">
                                                    <label class="checkbox checkbox-lg checkbox-light-primary checkbox-single flex-shrink-0">
                                                        <input type="radio"
                                                            class="answer"
                                                            name="details[{{ $dd->pertanyaan_id }}][answer]" value="1" @if($answer == 1) checked @endif>
                                                        <span></span>
                                                    </label>
                                                </div>
                                            </td>
                                            <td class="text-center width-60px valign-middle parent-group">
                                                <div class="d-inline-block">
                                                    <label class="checkbox checkbox-lg checkbox-light-primary checkbox-single flex-shrink-0">
                                                        <input type="radio"
                                                            class="answer"
                                                            name="details[{{ $dd->pertanyaan_id }}][answer]" value="2" @if($answer == 2) checked @endif>
                                                        <span></span>
                                                    </label>
                                                </div>
                                            </td>
                                            <td class="text-center width-60px valign-middle parent-group">
                                                <div class="d-inline-block">
                                                    <label class="checkbox checkbox-lg checkbox-light-primary checkbox-single flex-shrink-0">
                                                        <input type="radio"
                                                            class="answer"
                                                            name="details[{{ $dd->pertanyaan_id }}][answer]" value="3" @if($answer == 3) checked @endif>
                                                        <span></span>
                                                    </label>
                                                </div>
                                            </td>
                                            <td class="text-center width-60px valign-middle parent-group">
                                                <div class="d-inline-block">
                                                    <label class="checkbox checkbox-lg checkbox-light-primary checkbox-single flex-shrink-0">
                                                        <input type="radio"
                                                            class="answer"
                                                            name="details[{{ $dd->pertanyaan_id }}][answer]" value="4" @if($answer == 4) checked @endif>
                                                        <span></span>
                                                    </label>
                                                </div>
                                            </td>
                                            <td class="text-center width-60px valign-middle parent-group">
                                                <div class="d-inline-block">
                                                    <label class="checkbox checkbox-lg checkbox-light-primary checkbox-single flex-shrink-0">
                                                        <input type="radio"
                                                            class="answer"
                                                            name="details[{{ $dd->pertanyaan_id }}][answer]" value="5" @if($answer == 5) checked @endif>
                                                        <span></span>
                                                    </label>
                                                </div>
                                            </td>
                                        </tr>
                                        @endif
                                        @if ($loop->parent->last && $loop->last)
                                            <tr>
                                                <td class="text-left" colspan="7">
                                                    <label class="text-bold">{{ __('Deskripsi') }}</label>
                                                    <div class="parent-group">
                                                        <textarea name="keterangan" class="form-control"
                                                            placeholder="{{ __('Deskripsi') }}">{{ $record->keterangan }}</textarea>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endif

                                    @endforeach
                                @empty
                                    <tr>
                                        <td class="text-center" colspan="6">Data tidak tersedia!</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br>
    <div class="row">
        @php
            $tipe = $module;
        @endphp
        <div class="col-6">
            @include('pengajuan::tad.wawancara.flow')
        </div>
        <div class="col-6">
            <div class="card card-custom gutter-b" style="margin-bottom:0; height:100%;">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between p-4 flex-lg-wrap flex-xl-nowrap">
                        <div class="d-flex flex-column mr-5">
                            <span class="h4 text-dark text-hover-primary mb-5">
                                Informasi
                            </span>
                            <p class="text-dark-50">
                                Sebelum submit pastikan data Wawancara tersebut sudah sesuai.
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
