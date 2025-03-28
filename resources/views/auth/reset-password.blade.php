<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Tải tài nguyên CSS và JS đã biên dịch từ Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="hold-transition login-page">
    <div class="login-box">
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <a href="#" class="h1"><b>PMO</b> Assistant</a>
            </div>
            <div class="card-body">

                @if (session('status'))
                    <div style="margin-bottom: 1rem; color: green; font-weight: bold;">
                        {{ session('status') }}
                    </div>
                @else
                    <p>{{ __('auth.reset_password') }}</p>
                @endif

                <form method="POST" action="{{ route('password.store') }}">
                    @csrf
                    <input type="hidden" name="token" value="{{ $request->route('token') }}">

                    <!-- Email -->
                    @error('email')
                        <span class="text-danger text-sm">{{ $message }}</span>
                    @enderror
                    <div class="input-group mb-3">
                        <input type="email" name="email" class="form-control" placeholder="Email"
                            value="{{ old('email', $request->email) }}" required autofocus>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Mật khẩu mới -->
                    @error('password')
                        <span class="text-danger text-sm">{{ $message }}</span>
                    @enderror
                    <div class="input-group mb-3">
                        <input type="password" name="password" class="form-control" placeholder="{{ __('Password') }}"
                            required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Xác nhận mật khẩu -->
                    @error('password_confirmation')
                        <span class="text-danger text-sm">{{ $message }}</span>
                    @enderror
                    <div class="input-group mb-3">
                        <input type="password" name="password_confirmation" class="form-control"
                            placeholder="{{ __('Confirm Password') }}" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <button type="submit"
                            class="btn btn-primary btn-block">{{ __('Reset Password') }}</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

</body>

</html>
