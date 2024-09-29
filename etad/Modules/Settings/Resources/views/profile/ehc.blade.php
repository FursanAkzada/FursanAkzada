@extends('settings::layouts.profile.layout')

@section('title', $title)
@php
$user = auth()->user();

$labelTitle = $user->isEhc ? 'EHC' : '';

@endphp

@section('content-profile')
    <div class="card card-custom mb-3">
        <div class="card-body ribbon ribbon-top ribbon-ver">
            {{-- <div class="ribbon-target bg-danger" style="top: -2px; right: 20px;">
                {{ $labelTitle }}
            </div> --}}
            <div class="form-group row">
                <label for="" class="col-4 text-bold">Username <span class="float-right">:</span></label>
                <div class="col">
                    <label for="">{{ $user->username }}</label>
                </div>
            </div>
            <div class="form-group row">
                <label for="" class="col-4 text-bold">Nama <span class="float-right">:</span></label>
                <div class="col">
                    <label for="">{{ $user->name }}</label>
                </div>
            </div>
            <div class="form-group row">
                <label for="" class="col-4 text-bold">NIK <span class="float-right">:</span></label>
                <div class="col">
                    <label for="">{{ $user->nik ?? '-' }}</label>
                </div>
            </div>
            <div class="form-group row">
                <label for="" class="col-4 text-bold">Unit Kerja <span class="float-right">:</span></label>
                <div class="col">
                    <label for="">{{ $user->org_name??'' }}</label>
                </div>
            </div>
            <div class="form-group row">
                <label for="" class="col-4 text-bold">Jabatan <span class="float-right">:</span></label>
                <div class="col">
                    <label for="">{{ $user->position->name??'' }}</label>
                </div>
            </div>
            {{-- <div class="form-group row">
                <label for="" class="col-4 text-bold">Syncrhonized at <span class="float-right">:</span></label>
                <div class="col">
                    <label for="">{{ $user->updated_at->format('d/m/Y H:i:s') }}</label>
                </div>
            </div> --}}
            <div class="form-group row">
                <label for="" class="col-4 text-bold">Hak Akses <span class="float-right">:</span></label>
                <div class="col">
                    {{ $user->groups()->count() ? $user->groups()->first()->name : '-' }}
                    {{-- {{ $user->roles()->count() ? $user->roles()->first()->name : '-' }} --}}
                    {{-- <ul class="pl-5 mb-0">
            @foreach ($user->groups as $item)
              <li>{{ $item->name }}</li>
            @endforeach
          </ul> --}}
                </div>
            </div>
            <div class="form-group row">
                <label for="" class="col-4 text-bold">Email <span class="float-right">:</span></label>
                <div class="col">
                    <label for="">{{ $user->email??'' }}</label>
                </div>
            </div>
        </div>
    </div>
@endsection
