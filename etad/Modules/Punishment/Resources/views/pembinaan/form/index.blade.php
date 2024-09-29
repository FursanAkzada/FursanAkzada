@php
    $user = auth()->user();
    $so_id = $user->position->org_struct_id ?? null;

    $so_name = \Modules\Master\Entities\SO\OrgStruct::where('name', 'Divisi Human Capital')->first();
@endphp
@extends('layouts.grid.list')
@section('filters')
    <div class="mr-2">
        <input class="form-control filter-control" data-post="sk" placeholder="No SK" style="width: 150px">
    </div>
    <div class="mr-2">
        <input class="form-control filter-control" data-post="personil" placeholder="Personil TAD" style="width: 150px">
    </div>
    <div class="mr-2" style="width: 250px">
        <select class="filter-control form-control base-plugin--select2" id="unitKerjaCtrl" data-post="unit_kerja_id"
            title="Unit Kerja">
            <option selected disabled></option>
            {{-- env('APP_HC_ID') --}}
            @if ($user->isVendor || $user->checkPerms($perms . '.approve') || $so_id == $so_name->id)
                @foreach ($struct as $key => $group)
                    <optgroup label="{{ \Modules\Master\Entities\SO\OrgStruct::getLevelLabel($key) }}">
                        @foreach ($group as $val)
                            <option value="{{ $val->id }}">{{ $val->name }}</option>
                        @endforeach
                    </optgroup>
                @endforeach
            @elseif($so_id)
                <option value="{{ $so_id }}">{{ $user->org_name }}</option>
            @else
                <option value="{{ $user->id }}">{{ $user->name }}</option>
            @endif
        </select>
    </div>
    <div class="mr-2" style="width: 200px">
        <select class="filter-control form-control base-plugin--select2" id="vendorCtrl" data-post="vendor_id"
            title="Vendor">
            <option selected disabled></option>
            @foreach ($VENDOR as $item)
                @if ($user->isVendor)
                    @if ($user->vendor_id == $item->id)
                        <option value="{{ $item->id }}" data-kategori-id='{{ $item->categories->pluck('id') }}'
                            data-tads='{{ json_encode($item->tad) }}'>{{ $item->nama }}</option>
                    @endif
                @else
                    <option value="{{ $item->id }}" data-kategori-id='{{ $item->categories->pluck('id') }}'
                        data-tads='{{ json_encode($item->tad) }}'>{{ $item->nama }}</option>
                @endif
            @endforeach
        </select>
    </div>
    <div class="mr-2" style="width: 200px">
        <select class="filter-control form-control base-plugin--select2" data-post="jenis_id" title="Jenis Pembinaan">
            <option></option>
            @foreach (\App\Entities\EHC\JenisPunishment::pembinaan()->get() as $item)
                <option value="{{ $item->sandi }}">{{ $item->Lengkap }}</option>
            @endforeach
        </select>
    </div>
@endsection
@section('buttons-after')
    @if (
        \Route::has($route . '.create') &&
            auth()->user()->checkPerms('pu.pembinaan.add'))
        <a href="{{ route($route . '.create') }}" class="btn btn-info ml-2">
            <i class="fa fa-plus mr-1"></i> {{ __('Data') }}
        </a>
    @endif
@endsection
