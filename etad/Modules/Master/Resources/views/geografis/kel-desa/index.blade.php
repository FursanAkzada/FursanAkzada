@extends('layouts.grid.list')
@section('filters')
    <div class="mr-2">
        <input type="text" class="form-control filter-control" data-post="keyword" placeholder="Nama">
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
        $(document).on('change', 'select[name=unit_id]', function() {
            $.get(baseurl + '/master/so/jabatan/parent/' + $(this).val(), function(data) {
                $('select[name=parent_id]').empty();
                $('select[name=parent_id]').append('<option value="0">Tidak Ada</option>');
                $.each(data, function(key, value) {
                    $('select[name=parent_id]').append('<option value=' + value.id + '>' + value
                        .name + '</option>');
                });
            });
        })
    </script>
@endpush
