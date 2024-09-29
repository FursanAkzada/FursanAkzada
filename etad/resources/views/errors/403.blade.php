@extends('layouts.app')
@section('title', isset($title) ? $title . ' - Not Permitted' : 'Not Permitted')

@section('content')
    <div class="d-flex flex-column flex-root" style="height:400px">
        <!--begin::Error-->
        <div class="error error-5 d-flex flex-row-fluid bgi-size-cover bgi-position-center"
            style="background-image: url({{ '/assets/media/error/bg5.jpg' }});">
            <!--begin::Content-->
            <div class="container d-flex flex-row-fluid flex-column justify-content-md-center p-12">
                <h1 style="font-size: 4rem" class="font-weight-boldest text-info mt-10 mt-md-0 mb-12">Oops!</h1>
                <p class="font-weight-boldest display-4">You dont have permission this page.</p>
                <p class="font-size-h3">Please contact our help center or administrator</p>
            </div>
            <!--end::Content-->
        </div>
        <!--end::Error-->
    </div>
@endsection
