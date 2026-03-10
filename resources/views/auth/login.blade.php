@extends('layouts.auth')

@section('title', __('Log in'))

@section('content')
    <div class="login-box">
        <div class="login-logo">
            <a href="{{ url('/') }}"><b>{{ config('app.name') }}</b></a>
        </div>

        <div class="card">
            <div class="card-body login-card-body">
                @if (session('status'))
                    <div class="alert alert-info mb-3">
                        {{ session('status') }}
                    </div>
                @endif

                <p class="login-box-msg">{{ __('Sign in to start your session') }}</p>

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="input-group mb-3">
                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
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
                            autocomplete="current-password"
                            class="form-control @error('password') is-invalid @enderror"
                            placeholder="{{ __('Password') }}"
                        >
                        <div class="input-group-text"><span class="bi bi-lock-fill"></span></div>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-8">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="remember_me" name="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label" for="remember_me">{{ __('Remember me') }}</label>
                            </div>
                        </div>
                        <div class="col-4">
                            <button type="submit" class="btn btn-primary w-100">{{ __('Log in') }}</button>
                        </div>
                    </div>
                </form>

                @if (Route::has('password.request'))
                    <p class="mb-1 mt-3">
                        <a href="{{ route('password.request') }}">{{ __('I forgot my password') }}</a>
                    </p>
                @endif
            </div>
        </div>
    </div>
@endsection

