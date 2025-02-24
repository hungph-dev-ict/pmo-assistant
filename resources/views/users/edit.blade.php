@extends('layouts.app')

@section('page_title')
    User Edit
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('client.users.list', auth()->user()->tenant_id) }}">Users</a></li>
    <li class="breadcrumb-item active">User Edit</li>
@endsection

@section('inline_css')
    <!-- Tempus Dominus CSS -->
    <link rel="stylesheet" href="{{ asset('build/css/plugins/tempusdominus-bootstrap-4-css.css') }}">
    <link rel="stylesheet" href="{{ asset('build/css/plugins/select2-min-css.css') }}">
    <link rel="stylesheet" href="{{ asset('build/css/plugins/select2-bootstrap4-css.css') }}">
    @vite(['resources/js/plugins/toastr/toastr.min.css'])

    <style>
        .was-validated .custom-select:invalid,
        .custom-select.is-invalid {
            border-color: #dc3545;
            padding-right: calc(0.75em + 2.3125rem) !important;
            background: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='4' height='5' viewBox='0 0 4 5'%3e%3cpath fill='%23343a40' d='M2 0L0 2h4zm0 5L0 3h4z'/%3e%3c/svg%3e") right 0.75rem center/8px 10px no-repeat, #fff url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='none' stroke='%23dc3545' viewBox='0 0 12 12'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e") center right 1.75rem/calc(0.75em + 0.375rem) calc(0.75em + 0.375rem) no-repeat !important;
        }

        .select2-container--default .select2-selection--single.is-invalid {
            border-color: #dc3545;
            /* Màu đỏ cho lỗi */
            padding-right: calc(1.5em + 0.75rem);
            background-position: right calc(0.375em + 0.1875rem) center;
            background-repeat: no-repeat;
            background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-6">
            <!-- general form elements -->
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">User Edit Form</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <!-- /.card-header -->
                <form
                    action="{{ route('client.users.update', ['tenant_id' => auth()->user()->tenant_id, 'user_id' => $user->id]) }}"
                    method="POST">
                    @csrf
                    @method('PUT')
                    <!-- form start -->
                    <div class="card-body">
                        <div class="form-group">
                            <label for="userEmail">Email Address <span style="color: red;">*</span></label>
                            <input type="email" id="userEmail" name="user_email" placeholder="Enter Email"
                                class="form-control @error('user_email') is-invalid @enderror"
                                value="{{ old('user_email', $user->email) }}">
                            @error('user_email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('user_email') }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="userAccount">Account <span style="color: red;">*</span></label>
                            <input type="text" id="userAccount" name="user_account" placeholder="Enter Account"
                                class="form-control @error('user_account') is-invalid @enderror"
                                value="{{ old('user_account', $user->account) }}">
                            @error('user_account')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('user_account') }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="userName">Full Name <span style="color: red;">*</span></label>
                            <input type="text" id="userName" name="user_name" placeholder="Enter Name"
                                class="form-control @error('user_name') is-invalid @enderror"
                                value="{{ old('user_name', $user->name) }}">
                            @error('user_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('user_name') }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="userJobPosition">Main role<span style="color: red;">*</span></label>
                            <select id="userJobPosition" name="user_job_position"
                                class="form-control select2 @error('user_job_position') is-invalid @enderror">
                                <option selected disabled>-- Select Main Role --</option>
                                @foreach ($jobPositions as $jobPosition)
                                    <option value="{{ $jobPosition->key }}"
                                        {{ old('user_job_position', $user->job_position) == $jobPosition->key ? 'selected' : '' }}>
                                        {{ $jobPosition->value1 . ' - ' . $jobPosition->value2 }}
                                    </option>
                                @endforeach
                            </select>
                            @error('user_job_position')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('user_job_position') }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="userSubRole1">Sub Role 1</label>
                            <select id="userSubRole1" name="user_sub_role_1"
                                class="form-control select2 @error('user_sub_role_1') is-invalid @enderror"
                                style="width: 100%;">
                                <option value="" selected disabled></option>
                                @foreach ($jobPositions as $jobPosition)
                                    <option value="{{ $jobPosition->key }}"
                                        {{ old('user_sub_role_1', $user->sub_role_1) == $jobPosition->key ? 'selected' : '' }}>
                                        {{ $jobPosition->value1 . ' - ' . $jobPosition->value2 }}
                                    </option>
                                @endforeach
                            </select>
                            @error('user_sub_role_1')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('user_sub_role_1') }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="userSubRole2">Sub Role 2</label>
                            <select id="userSubRole2" name="user_sub_role_2"
                                class="form-control select2 @error('user_sub_role_2') is-invalid @enderror">
                                <option value="" selected disabled></option>
                                @foreach ($jobPositions as $jobPosition)
                                    <option value="{{ $jobPosition->key }}"
                                        {{ old('user_sub_role_2', $user->sub_role_2) == $jobPosition->key ? 'selected' : '' }}>
                                        {{ $jobPosition->value1 . ' - ' . $jobPosition->value2 }}
                                    </option>
                                @endforeach
                            </select>
                            @error('user_sub_role_2')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('user_sub_role_2') }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <input type="submit" value="Submit" class="btn btn-primary">
                    </div>
                </form>
            </div>
            <!-- /.card -->
        </div>
    </div>
    <!-- /.content -->
@endsection

@section('inline_js')
    <!-- Select2 -->
    <script src="{{ asset('build/js/plugins/select2-full-js.js') }}"></script>
    <!-- Moment.js from CDN -->
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
    {{-- <script src="{{ asset('build/js/plugins/moment-js.js') }}"></script> --}}
    <!-- Tempus Dominus JS -->
    <script src="{{ asset('build/js/plugins/tempusdominus-bootstrap-4-js.js') }}"></script>
    @vite(['resources/js/plugins/toastr/toastr.min.js'])
@endsection

@section('custom_inline_js')
    <script>
        $(function() {
            //Initialize Select2 Elements
            $('.select2').select2({
                placeholder: 'Select role', // Placeholder hiển thị khi không có lựa chọn
                allowClear: true // Bật tính năng xóa lựa chọn
            })
        });

        $(document).ready(function() {
            // Kiểm tra lỗi và thêm class vào phần tử Select2
            if ($('#userJobPosition').hasClass('is-invalid')) {
                $('#userJobPosition').next('.select2-container').find('.select2-selection')
                    .addClass(
                        'is-invalid');
            }

            if ($('#userSubRole1').hasClass('is-invalid')) {
                $('#userSubRole1').next('.select2-container').find('.select2-selection')
                    .addClass(
                        'is-invalid');
            }

            if ($('#userSubRole2').hasClass('is-invalid')) {
                $('#userSubRole2').next('.select2-container').find('.select2-selection')
                    .addClass(
                        'is-invalid');
            }
        });
    </script>
@endsection
