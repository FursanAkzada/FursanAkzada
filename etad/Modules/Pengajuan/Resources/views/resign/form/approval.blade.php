@extends('layouts.app')
@section('title', $title)
@section('buttons-after') @endsection
@section('content')
    <div class="card card-custom">
        <div class="card-header">
            <h5 class="card-title">
                Resign
            </h5>
            <button aria-label="Close" class="close" data-dismiss="card" onclick="location.href='{{ url()->previous() }}'"
                type="button">
                <i aria-hidden="true" class="ki ki-close"></i>
            </button>
        </div>
        <div class="card-body">
            @include('pengajuan::resign.form.tabs.pengajuan')
        </div>
        <div class="card-footer p-5">
            <form action="{{ route($route . '.approvalSave', $record->id) }}" method="POST">
                @csrf
                @method('POST')
                <input type="hidden" name="id" value="{{ $record->id }}">
                <div class="float-right d-flex flex-row">
                    @if ($approval = $record->checkApproval())
                        <input type="hidden" name="approval_id" value="{{ $approval->id }}">
                        <div class="btn-group dropup d-flex align-items-center">
                            <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                                <i class="far fa-save mr-2"></i>Approval
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <button type="button" class="dropdown-item" data-toggle="modal"
                                    data-target="#rejectModal">
                                    <i class="mr-3 far fa-save text-danger"></i> Reject
                                </button>
                                <button type="button" class="dropdown-item base-form--submit-page"
                                    data-swal-confirm="true" data-submit="approved">
                                    <i class="mr-2 far fa-save text-primary"></i> Approve
                                </button>
                            </div>
                        </div>
                        @include('pengajuan::tad.partials.modal-reject')
                    @endif
                </div>
            </form>
        </div>
    </div>
@endsection
