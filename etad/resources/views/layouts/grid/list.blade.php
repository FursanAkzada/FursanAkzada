@extends('layouts.app')
@section('title', $title)
@section('content')
@section('content-body')
    @yield('start-list')
	<!-- <style>
		.dataTables_wrapper.dt-bootstrap4.no-footer > .row:nth-child(1) {
			display: initial;
		}
	</style> -->
    <div class="row">
        <div class="col-lg-12">
            @includeWhen(empty($tableStruct['tabs']), 'layouts.grid.datatable')
            @includeWhen(!empty($tableStruct['tabs']), 'layouts.grid.datatableTabs')
        </div>
    </div>
    @yield('end-list')
@show
@endsection

@push('scripts')
	<script>
		$(function () {
			var toTime = function (date) {
				var ds = date.split('/');
				var year = ds[2];
				var month = ds[1];
				var day = ds[0];
				return new Date(year+'-'+month+'-'+day).getTime();
			}

			$('.mr-2').on('changeDate', 'input.date-start', function (value) {
				var me = $(this),
					startDate = new Date(value.date.valueOf()),
					date_end = me.closest('.input-group').find('input.date-end');

				if (me.val()) {
					if (toTime(me.val()) > toTime(date_end.val())) {
						date_end.datepicker('update', '')
							.datepicker('setStartDate', startDate)
							.prop('disabled', false);
					}
					else {
						date_end.datepicker('update', date_end.val())
							.datepicker('setStartDate', startDate)
							.prop('disabled', false);
					}
				}
				else {
					date_end.datepicker('update', '')
						.prop('disabled', true);
				}
			});
		});
	</script>
@endpush
