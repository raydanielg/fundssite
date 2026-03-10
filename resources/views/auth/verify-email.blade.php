@extends('layouts.auth')

@section('title', __('Verify Email'))

@section('content')
    <div class="login-box">
        <div class="login-logo">
            <a href="{{ url('/') }}"><b>{{ config('app.name') }}</b></a>
        </div>

        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">{{ __('Verify your email address') }}</p>

                <p class="mb-3">
                    {{ __('Thanks for signing up! Before getting started, please verify your email address by clicking on the link we just emailed to you.') }}
                </p>

                @if (session('status') == 'verification-link-sent')
                    <div class="alert alert-success mb-3">
                        {{ __('A new verification link has been sent to your email address.') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('verification.send') }}" class="mb-2">
                    @csrf
                    <button type="submit" class="btn btn-primary w-100">{{ __('Resend Verification Email') }}</button>
                </form>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-outline-secondary w-100">{{ __('Log Out') }}</button>
                </form>
            </div>
        </div>
    </div>
@endsection

