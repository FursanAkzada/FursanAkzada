@extends('layouts.grid.list')

{{--  --}}
@section('dataFilters')
    <a href="javascript:;" id="panel-filter-toggle" class="btn btn-primary mr-2"><i
            class="fas fa-search mr-2"></i>Pencarian</a>
    <div class="mt-4 d-flex align-items-center">
        <span class="mr-2">Hasil Pencarian : </span>
        <div id="output-filter"></div>
        {{-- <span class="badge badge-success mr-2">herianto</span> --}}
    </div>
@endsection

@section('panelFilters')
    <div class="form-group">
        <label for="">Keyword</label>
        <input type="text" class="form-control filter-control" data-post="keyword" placeholder="Pencarian">
    </div>
    <div class="form-group">
        <label for="">Berdasarkan Cabang</label>
        <select data-post="cab" id="" class="base-plugin--select2 form-control filter-control" title="Berdasarkan Cabang">
            <option value=""></option>
            @foreach (\App\Entities\EHC\Cabang::orderBy('KDCAPEM', 'asc')->get() as $item)
                @if (substr($item->KDCAPEM, -2) != '00')
                    <option value="{{ $item->KDCAB }}">--{{ $item->CAPEM }}</option>
                @else
                    <option value="{{ $item->KDCAB }}">{{ $item->CAPEM }}</option>
                @endif
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label for="">Berdasarkan Jabatan</label>
        <select data-post="jab" id="" class="base-plugin--select2 form-control filter-control" title="Berdasarkan Jabatan">
            <option value=""></option>
            @foreach (\App\Entities\EHC\Jabatan::get() as $item)
                <option value="{{ $item->idunit }}">{{ $item->NM_UNIT }}</option>
            @endforeach
        </select>
    </div>
    @if (auth()->user()->isEhc &&
        auth()->user()->checkPerms('master'))
        <div class="form-group">
            <label for="">Berdasarkan Vendor</label>
            <div class="mr-2">
                <select data-post="vendor" id="" class="form-control base-plugin--select2 filter-control"
                    title="Berdasarkan Vendor">
                    <option selected disabled></option>
                    @foreach (\Modules\Master\Entities\Vendor::get() as $item)
                        <option value="{{ $item->id }}">{{ $item->nama }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    @endif
    <div class="form-group">
        <label for="" class="mb-3">Status Bekerja</label>
        <div class="checkbox-inline">
            @if (auth()->user()->isVendor)
                <label class="checkbox">
                    <input type="checkbox" class="filter-control" data-post="worked" />
                    <span></span>
                    Sudah Bekerja
                </label>
                <label class="checkbox">
                    <input type="checkbox" class="filter-control" data-post="not_worked" />
                    <span></span>
                    Belum Bekerja
                </label>
            @endif
            <label class="checkbox">
                <input type="checkbox" class="filter-control" data-post="resign" />
                <span></span>
                Sudah Resign
            </label>
        </div>
    </div>
@endsection

@section('buttons-before')
    <a href="{{ route('master.sync.toMass') }}" class="btn btn-success ml-2 base-modal--confirm">
        <i class="fa fa-sync mr-1"></i> ETAD ({{ Modules\Master\Entities\Tad\Tad::count() }}) <i
            class="fas fa-arrow-right"></i> EHC ({{ App\Entities\EHC\Tad::count() }})
    </a>
@endsection
