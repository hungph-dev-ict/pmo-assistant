@extends('layouts.app')

@section('page_title')
    Change Your Password
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item active">Change Your Password</li>
@endsection

@section('content')
    <!-- Default box -->
    <div class="card col-4">
        <div class="card-header">
            <h3 class="card-title">Change Your Password</h3>

            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif


            <form method="POST" action="{{ route('password.custom-update') }}">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label>Mật khẩu hiện tại</label>
                    <input type="password" name="current_password" class="form-control" required>
                    @error('current_password')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label>Mật khẩu mới</label>
                    <input type="password" name="new_password" class="form-control" required>
                    @error('new_password')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label>Xác nhận mật khẩu mới</label>
                    <input type="password" name="new_password_confirmation" class="form-control" required>
                    @error('new_password_confirmation')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Cập nhật mật khẩu</button>
            </form>
        </div>
    </div>
@endsection
