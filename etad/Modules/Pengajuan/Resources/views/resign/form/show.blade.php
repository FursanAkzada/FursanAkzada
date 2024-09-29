@extends('layouts.app')
@section('title', $title)
@section('buttons-after') @endsection
@section('content')
    <div class="card card-custom">
        <div class="card-header">
            <h5 class="card-title">
                {!! $title !!}
            </h5>
            <button aria-label="Close" class="close" data-dismiss="card" onclick="location.href='{{ url()->previous() }}'"
                type="button">
                <i aria-hidden="true" class="ki ki-close"></i>
            </button>
        </div>
        <div class="card-body">
            @include('pengajuan::resign.form.tabs.pengajuan')
        </div>
    </div>
@endsection
