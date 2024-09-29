@extends('layouts.auth')
@section('content')
    <!--begin::Signin-->
    <div class="login-form login-signin">
        <div class="mb-10 text-center">
            <img src="{{ '/assets/media/logos/logo.png' }}" alt="bank jatim e-tad" style="width:70%">
        </div>
        <div class="mb-lg-10 mb-10">
            <h1 class="font-size-h1 font-weight-boldest">e-TAD</h1>
            <h1 class="font-size-h3">Sistem Informasi Tenaga Alih Daya</h1>
            <p class="text-muted font-weight-bold">Masuk untuk melanjutkan</p>
        </div>
        <!--begin::Form-->
        <form class="form" method="POST" action="{{ route('login') }}">
            @csrf
            <div class="form-group">
                <label class="font-weight-bolder">Email / Username</label>
                <input class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}"
                    placeholder="Masukan Email" name="email" autocomplete="off">
                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group">
                <label class="font-weight-bolder">Kata Sandi</label>
                <div class="input-group" id="show-password">
                    <input class="form-control @error('password') is-invalid @enderror" type="password"
                        placeholder="Masukan Kata Sandi" name="password" autocomplete="off" />
                    <div style="position: absolute; right: 12px; top: 12px; z-index:20;" tabindex="-1">
                        <a href="javascript:;" tabindex="-1"><i class="fa fa-eye-slash" aria-hidden="true"></i></a>
                    </div>
                </div>
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group">
                <label class="font-weight-bolder">Captcha</label>
                {{-- {!! captcha_img() !!} --}}
                {!! getCaptchaBox('captcha') !!}
                {{-- {{ $errors }} --}}
                {{-- {{ $errors->captcha }} --}}
                {{-- {{ $errors->get('captcha')[0]??'' }} --}}
                {{-- <input id="captcha" name="captcha" class="form-control @error('captcha') is-invalid @enderror"
                    placeholder="Captcha" autocomplete="off"> --}}
                @if ($errors->get('captcha')[0] ?? null)
                    {{-- @error('captcha') --}}
                    <span style="color: #F64E60; font-weight: 400; font-size: 10.8px">
                        <strong>{{ $errors->get('captcha')[0] ?? '' }}</strong>
                    </span>
                    {{-- @enderror --}}
                @endif
            </div>
            <!--begin::Action-->
            {{-- <div class="form-group">
            <label class="checkbox">
                <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                <span class="mr-2"></span>{{ __('Remember Me') }}
            </label>
        </div> --}}
            <div class="form-group d-flex justify-content-between align-items-center flex-wrap">
                <button type="submit" class="btn btn-danger btn-block font-weight-bold my-3 py-3">Masuk</button>
                {{-- <a href="{{ route('password.request') }}" class="w-100 text-center text-dark-50 text-hover-danger my-3 mr-2" id="">Lupa Password ?</a> --}}
            </div>
            <!--end::Action-->
        </form>
        <!--end::Form-->
    </div>
    <!--end::Signin-->
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            $('[name=captcha]').addClass('form-control');
            $("#show-password a").on('click', function(event) {
                event.preventDefault();
                if ($('#show-password input').attr("type") == "text") {
                    $('#show-password input').attr('type', 'password');
                    $('#show-password i').addClass("fa-eye-slash");
                    $('#show-password i').removeClass("fa-eye");
                } else if ($('#show-password input').attr("type") == "password") {
                    $('#show-password input').attr('type', 'text');
                    $('#show-password i').removeClass("fa-eye-slash");
                    $('#show-password i').addClass("fa-eye");
                }
            });
        });
    </script>
@endpush
