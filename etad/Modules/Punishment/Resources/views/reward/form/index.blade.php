@php
    $user = auth()->user();
    $ORG_STRUCTS = \Modules\Master\Entities\SO\OrgStruct::groupByLevel([], true);
    $GROUPS = \App\Entities\Group::orderBy('name', 'ASC')
    ->get();
    $so_id = $user->position->org_struct_id ?? null;
@endphp
@extends('layouts.grid.list')
@section('filters')
    <div class="mr-2">
        <input class="form-control filter-control" data-post="sk" placeholder="No SK" style="width: 150px">
    </div>
    <div class="mr-2">
        <input type="text"  data-post="personil" class="form-control filter-control" placeholder="{{ __('Personil TAD') }}" style="width: 150px">
    </div>
    <div class="mr-2" style="width:250px;">
        <select class="form-control base-plugin--select2 show-tick filter-control" data-post="unit_kerja_id" data-placeholder="Unit Kerja" id="tipeStrukturCtrl">
            <option value="">Unit Kerja</option>
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
        <select class="filter-control form-control base-plugin--select2" data-post="jenis_id" title="Jenis Penghargaan">
            <option></option>
            @foreach (\App\Entities\EHC\JenisPunishment::reward()->orderBy('Lengkap', 'ASC')->get() as $item)
                <option value="{{ $item->sandi }}">{{ $item->Lengkap }}</option>
            @endforeach
        </select>
    </div>
@endsection
@section('buttons-after')
    @if (
        \Route::has($route . '.create') &&
            auth()->user()->checkPerms('pu.reward.add'))
        <a href="{{ route($route . '.create') }}" class="btn btn-info ml-2">
            <i class="fa fa-plus mr-1"></i> {{ __('Data') }}
        </a>
    @endif
@endsection
