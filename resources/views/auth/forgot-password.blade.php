@extends('layouts.auth')

@section('title', __('Forgot Password'))

@section('content')
    <div class="login-box">
        <div class="login-logo">
            <a href="{{ url('/') }}"><b>{{ config('app.name') }}</b></a>
        </div>

        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">
                    {{ __('You forgot your password? Here you can easily retrieve a new password.') }}
                </p>

                @if (session('status'))
                    <div class="alert alert-success mb-3">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <div class="input-group mb-3">
                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            autofocus
                            class="form-control @error('email') is-invalid @enderror"
                            placeholder="{{ __('Email') }}"
                        >
                        <div class="input-group-text"><span class="bi bi-envelope"></span></div>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary w-100">{{ __('Send Password Reset Link') }}</button>
                        </div>
                    </div>
                </form>

                <p class="mt-3 mb-1">
                    <a href="{{ route('login') }}">{{ __('Back to login') }}</a>
                </p>
            </div>
        </div>
    </div>
@endsection
