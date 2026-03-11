@extends('layouts.admin')

@section('title', 'Profile')
@section('page_title', 'Profile')

@section('content')
    <div class="container-fluid">
        <div class="row g-4">
            <div class="col-12 col-lg-7">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 fw-bold">Profile Information</h5>
                        <div class="text-muted small">Update your name and email address.</div>
                    </div>
                    <div class="card-body">
                        @if (session('status') === 'profile-updated')
                            <div class="alert alert-success py-2">Saved.</div>
                        @endif

                        <form method="POST" action="{{ route('profile.update') }}" class="row g-3">
                            @csrf
                            @method('patch')

                            <div class="col-12">
                                <label class="form-label fw-semibold" for="name">Name</label>
                                <input id="name" name="name" type="text" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold" for="email">Email</label>
                                <input id="email" name="email" type="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required autocomplete="username">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 d-flex gap-2">
                                <button class="btn btn-primary px-4" type="submit">Save</button>
                                <a class="btn btn-outline-secondary" href="{{ url('/admin') }}">Back</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-5">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 fw-bold">Update Password</h5>
                        <div class="text-muted small">Use a long password to keep your account secure.</div>
                    </div>
                    <div class="card-body">
                        @if (session('status') === 'password-updated')
                            <div class="alert alert-success py-2">Saved.</div>
                        @endif

                        <form method="POST" action="{{ route('password.update') }}" class="row g-3">
                            @csrf
                            @method('put')

                            <div class="col-12">
                                <label class="form-label fw-semibold" for="current_password">Current password</label>
                                <input id="current_password" name="current_password" type="password" class="form-control @if($errors->updatePassword->has('current_password')) is-invalid @endif" autocomplete="current-password">
                                @if($errors->updatePassword->has('current_password'))
                                    <div class="invalid-feedback">{{ $errors->updatePassword->first('current_password') }}</div>
                                @endif
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold" for="password">New password</label>
                                <input id="password" name="password" type="password" class="form-control @if($errors->updatePassword->has('password')) is-invalid @endif" autocomplete="new-password">
                                @if($errors->updatePassword->has('password'))
                                    <div class="invalid-feedback">{{ $errors->updatePassword->first('password') }}</div>
                                @endif
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold" for="password_confirmation">Confirm password</label>
                                <input id="password_confirmation" name="password_confirmation" type="password" class="form-control @if($errors->updatePassword->has('password_confirmation')) is-invalid @endif" autocomplete="new-password">
                                @if($errors->updatePassword->has('password_confirmation'))
                                    <div class="invalid-feedback">{{ $errors->updatePassword->first('password_confirmation') }}</div>
                                @endif
                            </div>

                            <div class="col-12">
                                <button class="btn btn-outline-primary px-4" type="submit">Update</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 fw-bold text-danger">Delete Account</h5>
                        <div class="text-muted small">This action is permanent.</div>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('profile.destroy') }}" class="row g-3">
                            @csrf
                            @method('delete')

                            <div class="col-12">
                                <label class="form-label fw-semibold" for="delete_password">Confirm password</label>
                                <input id="delete_password" name="password" type="password" class="form-control @error('password', 'userDeletion') is-invalid @enderror" placeholder="Enter your password">
                                @error('password', 'userDeletion')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <button class="btn btn-danger px-4" type="submit">Delete</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
