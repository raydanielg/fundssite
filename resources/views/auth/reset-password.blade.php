@extends('layouts.auth')

@section('title', __('Reset Password'))

@section('content')
    <div class="login-box">
        <div class="login-logo">
            <a href="{{ url('/') }}"><b>{{ config('app.name') }}</b></a>
        </div>

        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">{{ __('You are only one step a way from your new password, recover your password now.') }}</p>

                <form method="POST" action="{{ route('password.store') }}">
                    @csrf

                    <input type="hidden" name="token" value="{{ $request->route('token') }}">

                    <div class="input-group mb-3">
                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email', $request->email) }}"
                            required
                            autofocus
                            autocomplete="username"
                            class="form-control @error('email') is-invalid @enderror"
                            placeholder="{{ __('Email') }}"
                        >
                        <div class="input-group-text"><span class="bi bi-envelope"></span></div>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="input-group mb-3">
                        <input
                            id="password"
                            type="password"
                            name="password"
                            required
                            autocomplete="new-password"
                            class="form-control @error('password') is-invalid @enderror"
                            placeholder="{{ __('Password') }}"
                        >
                        <div class="input-group-text"><span class="bi bi-lock-fill"></span></div>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="input-group mb-3">
                        <input
                            id="password_confirmation"
                            type="password"
                            name="password_confirmation"
                            required
                            autocomplete="new-password"
                            class="form-control @error('password_confirmation') is-invalid @enderror"
                            placeholder="{{ __('Confirm Password') }}"
                        >
                        <div class="input-group-text"><span class="bi bi-lock-fill"></span></div>
                        @error('password_confirmation')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary w-100">{{ __('Reset Password') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

