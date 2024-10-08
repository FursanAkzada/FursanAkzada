@extends('errors.layout')

@section('message')
    <div class="d-flex flex-column flex-root">
        <!--begin::Error-->
        <div class="error error-3 d-flex flex-row-fluid bgi-size-cover bgi-position-center"
            style="background-image: url('{{ asset('assets/media/error/bg3.jpg') }}');">
            <div class="px-md-30 py-md-0 d-flex flex-column justify-content-md-center px-10 py-10">
                <p class="display-4 font-weight-boldest mb-12 text-white">
                    SELAMAT DATANG,
                    <span class="font-italic">
                        {{ strtoupper(auth()->user()->name) }}
                    </span>
                </p>
                <p class="font-size-h2 line-height-md">
                    @if (auth()->user()->status == 'waiting.verification')
                        Mohon maaf, Akun Anda sedang dalam proses verifikasi oleh Administrator!
                    @else
                        Mohon maaf, Akun Anda sedang dinonaktifkan!
                    @endif
                </p>
                <form action="<?= route('logout') ?>" method="POST">
                    @csrf
                    @method('POST')
                    <button type="submit" class="btn btn-light-primary font-weight-bold">Logout</button>
                </form>
            </div>
        </div>
    </div>
@endsection
