@extends('layouts.app')
@section('title', $title)
@section('buttons-after') @endsection
@section('content')
    <form action="{{ route($route . '.keluarga.store', $record->id) }}" autocomplete="off" method="post">
        @csrf
        <div class="card card-custom mb-5">
            <div class="card-header">
                <div class="card-title">
                    <span class="card-icon">
                        <i class="fas fa-user text-primary"></i>
                    </span>
                    <h4 class="card-label">Informasi Tenaga Ahli Daya</h4>
                </div>
                <button aria-label="Close" class="close" data-dismiss="card"
                    onclick="location.href='{{ route($route . '.index') }}'" type="button">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="card-body">
                <table class="table table-bordered w-100">
                    <tr>
                        <td><b>Nama</b></td>
                        <td style="width: 20px">:</td>
                        <td>{{ $record->nama }}</td>
                        <td><b>Pendidikan Terakhir</b></td>
                        <td style="width: 20px">:</td>
                        <td>
                		{{ $record->pendidikan->name ?? '' }}
                		@if ($record->jurusan)
                    			{{ ', ' . $record->jurusan->name }}
                		@endif
                		@if ($record->gelar)
                    			{{ ', ' . $record->gelar }}
                		@endif
			</td>
                    </tr>
                    <tr>
                        <td><b>NIK</b></td>
                        <td style="width: 20px">:</td>
                        <td>{{ $record->nik }}</td>
                        <td><b>Alamat</b></td>
                        <td style="width: 20px">:</td>
                        <td>{{ $record->alamat_lengkap }}</td>
                        <tr>
                    </tr>
                        <td><b>Tempat & Tgl Lahir</b></td>
                        <td style="width: 20px">:</td>
                        <td>
                            {{ $record->tempat_lahir }},
                            {{ $record->tanggal_lahir ? $record->tanggal_lahir->format('d/m/Y') : '' }}
                        </td>
                        <td><b>NPWP</b></td>
                        <td style="width: 20px">:</td>
                        <td>{{ $record->npwp }}</td>
                    </tr>
                    <tr>
                        <td><b>Status Pernikahan</b></td>
                        <td style="width: 20px">:</td>
                        <td>{{ $record->status_perkawinan }}</td>
                        <td><b>Telepon</b></td>
                        <td style="width: 20px">:</td>
                        <td>{{ $record->telepon }}</td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="card card-custom">
            <div class="card-header">
                <div class="card-title">
                    <span class="card-icon">
                        <i class="fas fa-users text-primary"></i>
                    </span>
                    <h5 class="card-label">Informasi Keluarga<small>({{ $record->nama }})</small></h5>
                </div>
                <div class="card-toolbar">
                    <a href="{{ route($route . '.keluarga.detailCreate', $record->id) }}"  data-placement="bottom" class="btn btn-primary base-modal--render" data-modal-backdrop="false" data-modal-v-middle="false" data-modal-size="modal-xl"><i class="fas fa-plus"></i>
                        Tambah Anggota Keluarga</a>
                </div>
            </div>
            <div class="card-body">
                @if (isset($tableStruct['datatable_1']))
                    <table id="datatable_1" class="table table-bordered table-hover is-datatable hide" style="width: 100%;"
                        data-url="{{ isset($tableStruct['url']) ? $tableStruct['url'] : route($route . '.grid') }}"
                        data-paging="{{ $paging ?? true }}" data-info="{{ $info ?? true }}">
                        <thead>
                            <tr>
                                @foreach ($tableStruct['datatable_1'] as $struct)
                                    <th class="text-center v-middle" data-columns-name="{{ $struct['name'] ?? '' }}"
                                        data-columns-data="{{ $struct['data'] ?? '' }}"
                                        data-columns-label="{{ $struct['label'] ?? '' }}"
                                        data-columns-sortable="{{ $struct['sortable'] === true ? 'true' : 'false' }}"
                                        data-columns-width="{{ $struct['width'] ?? '' }}"
                                        data-columns-class-name="{{ $struct['className'] ?? '' }}"
                                        style="{{ isset($struct['width']) ? 'width: ' . $struct['width'] . '; ' : '' }}">
                                        {{ $struct['label'] }}
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @yield('tableBody')
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </form>

@endsection
