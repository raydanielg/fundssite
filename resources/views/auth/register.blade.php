@extends('layouts.auth')

@section('title', __('Register'))
@section('body_class', 'register-page')

@section('content')
    <div class="register-box">
        <div class="register-logo">
            <a href="{{ url('/') }}"><b>{{ config('app.name') }}</b></a>
        </div>

        <div class="card">
            <div class="card-body register-card-body">
                <p class="register-box-msg">{{ __('Register a new membership') }}</p>

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="input-group mb-3">
                        <input
                            id="name"
                            type="text"
                            name="name"
                            value="{{ old('name') }}"
                            required
                            autofocus
                            autocomplete="name"
                            class="form-control @error('name') is-invalid @enderror"
                            placeholder="{{ __('Name') }}"
                        >
                        <div class="input-group-text"><span class="bi bi-person-fill"></span></div>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="input-group mb-3">
                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
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

                    <button type="submit" class="btn btn-primary w-100">{{ __('Register') }}</button>
                </form>

                <a href="{{ route('login') }}" class="text-center mt-3 d-block">{{ __('I already have a membership') }}</a>
            </div>
        </div>
    </div>
@endsection

