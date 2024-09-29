@extends('layouts.app')
@section('title', $title)

@section('buttons-after')
@endsection

@section('content')
    <form action="{{ route($route . '.update', $record->id) }}" method="post">
        @csrf
        @method('PATCH')
        <div class="card card-custom">
            <div class="card-header">
                <h5 class="card-title">
                    Assign Approval |
                    <span class="label label-xl label-danger label-inline text-nowrap">{{ $record->name }}</span>
                </h5>
                <button aria-label="Close" class="close" data-dismiss="card"
                    onclick="location.href='{{ route($route . '.index') }}'" type="button">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 30px;">No</th>
                            <th class="text-center">Hak Akses</th>
                            <th class="text-center" style="width: 100px;">Sekuensial</th>
                            <th class="text-center" style="width: 100px;">Paralel</th>
                            <th class="text-center valign-middle" style="width: 100px;">
                                <div class="d-flex justify-content-center">
                                    <button type="button" class="btn btn-sm btn-icon btn-info btn-circle add-flow"><i
                                            class="fa fa-plus"></i></button>
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($record->flows as $row)
                            <tr data-key="{{ $loop->iteration }}">
                                <td class="text-center no">{{ $loop->iteration }}</td>
                                <td class="text-left parent-group">
                                    <input type="hidden" name="flows[{{ $loop->iteration }}][group_id]"
                                        value="{{ $row->id }}">
                                    <select name="flows[{{ $loop->iteration }}][group_id]"
                                        class="form-control base-plugin--select2-ajax"
                                        data-url="{{ route('settings.roles.selectRole', ['search' => 'approver', 'perms' => $record->module]) }}"
                                        data-placeholder="{{ __('Pilih Salah Satu') }}">
                                        @foreach ($groups as $val)
                                            <option value="{{ $val->id }}"
                                                {{ $val->id == $row->group_id ? 'selected' : '' }}>{{ $val->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="text-center parent-group">
                                    <label class="checkbox checkbox-lg d-inline-block checkbox-rounded">
                                        <input type="radio" class="approve check"
                                            name="flows[{{ $loop->iteration }}][type]" value="1"
                                            {{ $row->type == 1 ? 'checked' : '' }}>
                                        <span></span>
                                    </label>
                                </td>
                                <td class="text-center parent-group">
                                    <label class="checkbox checkbox-lg d-inline-block checkbox-rounded">
                                        <input type="radio" class="approve check"
                                            name="flows[{{ $loop->iteration }}][type]" value="2"
                                            {{ $row->type == 1 ? '' : 'checked' }}>
                                        <span></span>
                                    </label>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center">
                                        <button type="button" class="btn btn-danger btn-pill btn-sm btn-icon remove-flow"
                                            {{ $loop->count <= 1 ? 'qc-remove-disabled' : '' }}><i
                                                class="fa fa-trash"></i></button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr data-key="1">
                                <td class="text-center no">1</td>
                                <td class="text-left parent-group">
                                    <select name="flows[1][group_id]" class="form-control base-plugin--select2-ajax"
                                        data-url="{{ route('settings.roles.selectRole', ['search' => 'approver', 'perms' => $record->module]) }}"
                                        data-placeholder="{{ __('Pilih Salah Satu') }}">
                                    </select>
                                </td>
                                <td class="text-center parent-group">
                                    <label class="checkbox checkbox-lg d-inline-block checkbox-rounded">
                                        <input type="radio" class="approve check" name="flows[1][type]" value="1" checked>
                                        <span></span>
                                    </label>
                                </td>
                                <td class="text-center parent-group">
                                    <label class="checkbox checkbox-lg d-inline-block checkbox-rounded">
                                        <input type="radio" class="approve check" name="flows[1][type]" value="2">
                                        <span></span>
                                    </label>
                                </td>
                                <td class="text-center justify-content-center">
                                    <div class="d-flex justify-content-center">
                                        <button type="button" class="btn btn-danger btn-pill btn-sm btn-icon remove-flow"
                                            qc-remove-disabled><i class="fa fa-trash"></i></button>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                <div class="d-flex justify-content-between">
                    <x-btn-back class="mr-2" url="{{ route($route . '.index') }}" />
                    <x-btn-save via="base-form--submit-page" />
                </div>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
    <script>
        $(function() {
            var refreshNo = function(tbody) {
                $(tbody).find('.no').each(function(i) {
                    $(this).html(i + 1);
                });
                $(tbody).find('.remove-flow').prop('disabled', false);
                if ($(tbody).find('.remove-flow').length <= 1) {
                    // $(tbody).find('.remove-flow').prop('disabled', true);
                }
            }

            $(document).on('click', '.add-flow', function(e) {
                var me = $(this),
                    tbody = me.closest('table').find('tbody').first(),
                    key = tbody.find('tr').length ? parseInt(tbody.find('tr').last().data('key')) + 1 : 1;

                var template = `
                <tr data-key="` + key + `">
                    <td class="text-center no">` + key + `</td>
                    <td class="text-left parent-group">
                        <select name="flows[` + key + `][group_id]"
                            class="form-control base-plugin--select2-ajax"
                            data-url="{{ route('settings.roles.selectRole', ['search' => 'approver', 'perms' => $record->module]) }}"
                            data-placeholder="{{ __('Pilih Salah Satu') }}">
                        </select>
                    </td>
                    <td class="text-center parent-group">
                        <label class="checkbox checkbox-lg d-inline-block checkbox-rounded">
                            <input type="radio" class="approve check" name="flows[` + key + `][type]" value="1" checked>
                            <span></span>
                        </label>
                    </td>
                    <td class="text-center parent-group">
                        <label class="checkbox checkbox-lg d-inline-block checkbox-rounded">
                            <input type="radio" class="approve check" name="flows[` + key + `][type]" value="2">
                            <span></span>
                        </label>
                    </td>
                    <td class="text-center justify-content-center">
                        <div class="d-flex justify-content-center">
                            <button type="button" class="btn btn-danger btn-pill btn-sm btn-icon remove-flow"><i class="fa fa-trash"></i></button>
                        </div>
                    </td>
                </tr>
            `;

                tbody.append(template);
                refreshNo(tbody);
                BasePlugin.initSelect2();
            });

            $(document).on('click', '.remove-flow', function(e) {
                var me = $(this),
                    tbody = me.closest('table').find('tbody').first();

                me.closest('tr').remove();
                refreshNo(tbody);
                BasePlugin.initSelect2();
            });
        });
    </script>
@endpush
