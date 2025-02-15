@extends('layouts.app')

@section('inline_css')
    @vite(['resources/js/plugins/toastr/toastr.min.css'])
@endsection

@section('page_title')
    User Add
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('client.users.list', auth()->user()->tenant_id) }}">Users</a></li>
    <li class="breadcrumb-item active">User Add</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-6">
            <!-- general form elements -->
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">By Form</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <!-- /.card-header -->
                <form action="{{ route('client.users.store.form', ['tenant_id' => auth()->user()->tenant_id]) }}"
                    method="POST">
                    @csrf
                    <!-- form start -->
                    <div class="card-body">
                        <div class="form-group">
                            <label for="userEmail">Email Address <span style="color: red;">*</span></label>
                            <input type="email" id="userEmail" name="user_email" placeholder="Enter Email"
                                class="form-control @error('user_email') is-invalid @enderror"
                                value="{{ old('user_email') }}">
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
                                value="{{ old('user_account') }}">
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
                                value="{{ old('user_name') }}">
                            @error('user_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('user_name') }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="userJobPosition">Job Position <span style="color: red;">*</span></label>
                            <select id="userJobPosition" name="user_job_position"
                                class="form-control select @error('user_job_position') is-invalid @enderror">
                                <option selected disabled>-- Select Job Position --</option>
                                @foreach ($jobPositions as $jobPosition)
                                    <option value="{{ $jobPosition->key }}"
                                        {{ old('user_job_position') == $jobPosition->key ? 'selected' : '' }}>
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
                            <label for="userPassword">Password <span style="color: red;">*</span></label>
                            <input type="password" id="userPassword" name="user_password"
                                value="{{ old('user_password') }}"
                                class="form-control @error('user_password') is-invalid @enderror">
                            @error('user_password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('user_password') }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-check">
                            <input type="checkbox" id="showPassword" class="form-check-input">
                            <label class="form-check-label" for="showPassword">Show Password</label>
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

        <div class="col-md-6" id="upfile-create-users-element">
            <upfile-create-users
                submit-url="{{ route('client.users.store', ['tenant_id' => auth()->user()->tenant_id]) }}"></upfile-create-users>
        </div>

        <div class="col-md-12" id="bulk-insert-element">
            <bulk-insert-users
                submit-url="{{ route('client.users.store', ['tenant_id' => auth()->user()->tenant_id]) }}"></bulk-insert-users>
        </div>
    </div>
    <!-- /.content -->
@endsection

@section('inline_js')
    @vite(['resources/js/plugins/toastr/toastr.min.js'])
@endsection

@section('custom_inline_js')
    <script>
        function submitAddByForm() {
            document.getElementById("addByForm").submit();
        }

        function submitBulkInsertForm() {
            document.getElementById("bulkInsertForm").submit();
        }

        function generateRandomString(length = 12) {
            const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
            let result = '';
            const charactersLength = characters.length;
            for (let i = 0; i < length; i++) {
                result += characters.charAt(Math.floor(Math.random() * charactersLength));
            }
            return result;
        }
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const passwordInput = document.getElementById('userPassword');
            const showPasswordCheckbox = document.getElementById('showPassword');
            passwordInput.value = generateRandomString(12);

            // Hiển thị/Ẩn mật khẩu dựa trên trạng thái checkbox showPassword
            showPasswordCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    passwordInput.type = 'text'; // Hiển thị mật khẩu
                } else {
                    passwordInput.type = 'password'; // Ẩn mật khẩu
                }
            });
        });
    </script>
@endsection
