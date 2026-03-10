@extends('layouts.auth')

@section('title', __('Confirm Password'))

@section('content')
    <div class="login-box">
        <div class="login-logo">
            <a href="{{ url('/') }}"><b>{{ config('app.name') }}</b></a>
        </div>

        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">
                    {{ __('Please confirm your password before continuing.') }}
                </p>

                <form method="POST" action="{{ route('password.confirm') }}">
                    @csrf

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
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary w-100">{{ __('Confirm') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
