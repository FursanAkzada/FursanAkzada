@extends('settings::layouts.profile.layout')

@section('title', $title)

@section('content-profile')
    <form action="{{ route($route . '.store') }}" method="POST">
        @csrf
        <div class="card card-custom">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-10 offset-md-1">
                        <div class="row form-group">
                            <label for="" class="col-3 text-bold text-right">Password Lama</label>
                            <div class="col parent-group toggle-password">
                                <input type="password" name="old_password" id="" class="form-control"
                                    placeholder="Password Lama">
                                <div style="position: absolute; right: 20px; top: 12px; z-index:20;" tabindex="-1">
                                    <a href="javascript:;" tabindex="-1"><i class="fa fa-eye-slash"
                                            aria-hidden="true"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="row form-group">
                            <label for="" class="col-3 text-bold text-right">Password Baru</label>
                            <div class="col parent-group toggle-password">
                                <input type="password" name="new_password" id="" class="form-control"
                                    placeholder="Password Baru">
                                <div style="position: absolute; right: 20px; top: 12px; z-index:20;" tabindex="-1">
                                    <a href="javascript:;" tabindex="-1"><i class="fa fa-eye-slash"
                                            aria-hidden="true"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="row form-group">
                            <label for="" class="col-3 text-bold text-right">Konfirmasi Password</label>
                            <div class="col parent-group toggle-password">
                                <input type="password" name="new_password_confirmation" id="" class="form-control"
                                    placeholder="Konfirmasi Password Baru">
                                <div style="position: absolute; right: 20px; top: 12px; z-index:20;" tabindex="-1">
                                    <a href="javascript:;" tabindex="-1"><i class="fa fa-eye-slash"
                                            aria-hidden="true"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer p-5">
                <div class="d-flex float-right flex-row">
                    {{-- <x-btn-back class="mr-2" url="{{ route($route.'.index') }}" /> --}}
                    <x-btn-save via="base-form--submit-page" />
                </div>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
    <script>
        $(".toggle-password a").on('click', function(event) {
            event.preventDefault();
            if ($(this).parent().parent().find('input').attr("type") == "text") {
                $(this).parent().parent().find('input').attr('type', 'password');
                $(this).parent().parent().find('i').addClass("fa-eye-slash");
                $(this).parent().parent().find('i').removeClass("fa-eye");
            } else if ($(this).parent().parent().find('input').attr("type") == "password") {
                $(this).parent().parent().find('input').attr('type', 'text');
                $(this).parent().parent().find('i').removeClass("fa-eye-slash");
                $(this).parent().parent().find('i').addClass("fa-eye");
            }
        });
    </script>
@endpush
