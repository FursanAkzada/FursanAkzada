@php
    // dd(11);
    $user = auth()->user();
    $ORG_STRUCTS = \Modules\Master\Entities\SO\OrgStruct::groupByLevel([], true);
    $GROUPS = \App\Entities\Group::orderBy('name', 'ASC')->get();
@endphp

@push('styles')
    <style>
        #loader {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            width: 100%;
            background: rgba(255, 255, 255, 0.339) url('{{ URL::asset('assets/media/ui/spinner.gif') }}') no-repeat center center;
            z-index: 10000;
        }
    </style>
@endpush

@extends('layouts.grid.list')
@section('filters')
    <div class="mr-2">
        <input type="text" class="form-control filter-control" data-post="name" placeholder="Nama & NIO" style="width: 200px">
    </div>
    <div class="mr-2" style="width: 200px">
        <select class="form-control base-plugin--select2 show-tick filter-control" data-post="jabatan_id"
            data-placeholder="Posisi TAD">
            <option value="">(Posisi TAD)</option>
            @foreach ($KATEGORI_VENDOR as $kategori_vendor)
                <optgroup label="{{ $kategori_vendor->nama }}">
                    @foreach ($kategori_vendor->jabatanTad as $item)
                        <option value="{{ $item->idunit }}">{{ $item->NM_UNIT }}</option>
                    @endforeach
                </optgroup>
            @endforeach
        </select>
    </div>
    @if ($route != 'personil.belum-bekerja')
        <div class="mr-2" style="width: 250px">
            <select class="form-control base-plugin--select2 show-tick filter-control" data-post="unit_kerja_id"
                data-placeholder="Unit Kerja">
                <option value="">(Unit Kerja)</option>
                @if (
                    ($user->position_id &&
                        ($user->position->struct->code === 'A35' ||
                            (isset($user->position->struct->parent->code) && $user->position->struct->parent->code === 'A35'))) ||
                        $user->is_vendor)
                    @foreach ($ORG_STRUCTS as $level => $group)
                        @foreach ($group as $val)
                            @if ($loop->first)
                                <optgroup label="{{ \Modules\Master\Entities\SO\OrgStruct::getLevelLabel($level) }}">
                            @endif
                            <option value="{{ $val->id }}">{{ $val->name }}</option>
                            @if ($loop->last)
                                </optgroup>
                            @endif
                        @endforeach
                    @endforeach
                @else
                    <optgroup label="{{ ucfirst($user->position->struct->level) }}">
                        <option value="{{ $user->position->struct->id }}">{{ $user->position->struct->name }}</option>
                    </optgroup>
                @endif
            </select>
        </div>
    @endif
    <div class="mr-2" style="width: 250px">
        <select class="form-control base-plugin--select2 show-tick filter-control" data-post="vendor_id"
            data-placeholder="Vendor">
            <option value="">(Vendor)</option>
            @foreach ($VENDOR as $item)
                @if ($user->isVendor)
                    @if ($user->vendor_id == $item->id)
                        <option value="{{ $item->id }}">{{ $item->nama }}</option>
                    @endif
                @else
                    <option value="{{ $item->id }}">{{ $item->nama }}</option>
                @endif
            @endforeach
        </select>
    </div>
    @if ($route == 'personil.belum-bekerja')
    @else
	<div class="mr-2" style="width: 200px">
        <select class="form-control base-plugin--select2 show-tick filter-control" data-post="status"
            data-placeholder="Status">
            <option disabled selected value="">Status</option>
            <option value="work">Bekerja</option>
            <option value="resign">Resign</option>
        </select>
    </div>
    @endif
    <div id="loader">
    </div>
@endsection

@section('buttons-before')
@endsection

@section('buttons-after')
    {{-- {{ dd(\Route::has($route . '.create'), $perms, auth()->user()->checkPerms($perms . '.add')) }} --}}
    @if ($module === 'personil.migrasi')
        {{-- @if (\Route::has($route . '.create') &&
    auth()->user()->checkPerms($perms . '.add'))
            @include('globals.btnAddImport')
        @endif --}}
        @if (Modules\Master\Entities\Tad\Tad::whereHas('kepegawaian', function ($q) {
                $q->whereIn('status', [
                    \Modules\Master\Entities\Tad\Kepegawaian::WORK,
                    \Modules\Master\Entities\Tad\Kepegawaian::MIGRATE,
                ]);
            })->get()->first())
            <a href="{{ route('personil.migrasi.importSaveConfirm', ['tipe' => 'aktif']) }}"
                class="btn btn-primary base-modal--render ml-2" data-modal-backdrop="false" data-modal-v-middle="true"
                data-original-title="Migrasi" toggle="tooltip" data-placement="middle">
                <i class="fa fa-sync mr-1"></i> {{ __('Bekerja') }}
            </a>
        @else
            <a href="{{ route('personil.migrasi.import-save', ['tipe_import' => 'aktif']) }}"
                class="btn btn-primary loader-button ml-2">
                <i class="fa fa-sync mr-1"></i> {{ __('Bekerja') }}
            </a>
        @endif
        @if (Modules\Master\Entities\Tad\Tad::whereHas('lastEmployment', function ($q) {
                $q->whereIn('status', [
                    \Modules\Master\Entities\Tad\Kepegawaian::RESIGN,
                    \Modules\Master\Entities\Tad\Kepegawaian::END,
                ]);
            })->get()->first())
            <a href="{{ route('personil.migrasi.importSaveConfirm', ['tipe' => 'nonaktif']) }}"
                class="btn btn-info base-modal--render ml-2" data-modal-backdrop="false" data-modal-v-middle="true"
                data-original-title="Migrasi" toggle="tooltip" data-placement="middle">
                <i class="fa fa-sync mr-1"></i> {{ __('Resign') }}
            </a>
        @else
            <a class="btn btn-info loader-button ml-2"
                href="{{ route('personil.migrasi.import-save', ['tipe_import' => 'nonaktif']) }}">
                <i class="fa fa-sync mr-1"></i> {{ __('Resign') }}
            </a>
        @endif
        {{-- <div class="btn-group dropdown ml-2">
            <button type="button" class="btn btn-primary dropdown-toggle"
                data-toggle="dropdown"
                aria-haspopup="true"
                aria-expanded="false">
                <i class="mr-1 fa fa-save"></i>
                {{ __('Migrasi') }}
            </button>
            <div class="dropdown-menu dropdown-menu-right">
                <a href="{{ route($route.'.import') }}"
                    class="dropdown-item align-items-center base-modal--render">
                    <i class="mr-2 fa fa-upload text-primary"></i>
                    {{ __('From API') }}
                </a>
            </div>
        </div> --}}
    @endif
    @if ($module === 'personil.unemployed')
        @if (
            \Route::has($route . '.create') &&
                auth()->user()->checkPerms($perms . '.add'))
            <a href="{{ route($route . '.create') }}" class="btn btn-info ml-2">
                <i class="fa fa-plus mr-1"></i> {{ __('Data') }}
            </a>
        @endif
    @endif
    {{-- @if ($module === 'quota.personil')
        <a href="{{ route('getMaintenance') }}" class="btn btn-info base-modal--render ml-2" data-modal-backdrop="false"
            data-modal-v-middle="true" data-original-title="Migrasi" toggle="tooltip" data-placement="middle">
            <i class="fa fa-paper-plane mr-1"></i> {{ __('Migrasi') }}
        </a>
    @endif --}}
@endsection

@push('scripts')
    <script>
        $('.loader-button').on('click', function() {
            var spinner = $('#loader');
            spinner.show();
        });
    </script>
@endpush
