@php
    $org_struct_map = [
        'bod' => 'Direksi',
        'vice' => 'SEVP',
        'division' => 'Divisi',
        // 'departemen' => 'Sub Divisi',
        'cabang' => 'Cabang',
        'capem' => 'Cabang Pembantu',
        'kas' => 'Kantor Kas',
    ];
    $ORG_STRUCT = \Modules\Master\Entities\SO\OrgStruct::groupByLevel();
    // dd(22, json_decode($ORG_STRUCT['bod']));
@endphp
@extends('layouts.grid.list')
@push('styles')
    <style type="text/css">
        .select2-container--default .select2-results__options {
            margin-left: 20px;
        }
    </style>
@endpush
@section('filters')
    <div class="mr-2">
        <input type="text" class="form-control filter-control" data-post="name" placeholder="Nama">
    </div>
    <div class="mr-2" style="width: 200px">
        <select class="form-control base-plugin--select2 show-tick filter-control" data-post="level"
            data-placeholder="Tipe Struktur" id="tipeStrukturCtrl">
            <option value="">Tipe Struktur</option>
            @foreach ($org_struct_map as $key => $label)
                <option value="{{ $key }}" data-structs='@json($ORG_STRUCT[$key])'>
                    {{ $label }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="mr-2" style="width: 300px">
        <select class="form-control base-plugin--select2 show-tick filter-control" data-post="org_struct_id"
            data-placeholder="Struktur" id="strukturCtrl">
            <option value="">Struktur</option>
        </select>
    </div>
@endsection
@section('buttons-before')
    {{-- <div class="btn-group dropdown">
  <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    Export
  </button>
  <div class="dropdown-menu">
    <a class="dropdown-item" href="#">Excel</a>
    <a class="dropdown-item" href="#">PDF</a>
  </div>
</div> --}}
@endsection

@push('scripts')
    <script>
        $(document).on('change', '#tipeStrukturCtrl', function(e) {
            var structs = $('#tipeStrukturCtrl option:selected').data('structs');
            let options = `<option disabled selected value=""></option>`;
            for (let item of structs) {
                options += `<option value='${item.id}'>${item.name}</option>`;
            }
            $('#strukturCtrl').select2('destroy');
            $('#strukturCtrl').html(options);
            $('#strukturCtrl').select2();
        });
    </script>
@endpush
