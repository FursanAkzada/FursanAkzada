@extends('layouts.grid.list')
@section('filters')
    <div class="mr-2">
        <input type="text" class="form-control filter-control" data-post="keyword" placeholder="Nama">
    </div>
    <div class="mr-2" style="width: 200px">
        <select class="form-control base-plugin--select2 show-tick filter-control" data-post="kategori_id"
            data-placeholder="Kategori TAD">
            <option value="">Kategori TAD</option>
            @foreach ($KATEGORI as $item)
                <option value="{{ $item->id }}">{{ $item->nama }}</option>
            @endforeach
        </select>
    </div>
    <div class="mr-2" style="width: 200px">
        <select class="form-control base-plugin--select2 filter-control" data-post="jenis" data-placeholder="Jenis">
            <option value="">Jenis</option>
            <option value="J-901">Administrasi</option>
            <option value="J-902">Non Administrasi</option>
        </select>
    </div>
@endsection

