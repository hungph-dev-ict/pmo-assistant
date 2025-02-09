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
                <!-- form start -->
                <div class="card-body">
                    <form id="addByForm">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Email address</label>
                            <input type="email" class="form-control" id="exampleInputEmail1" placeholder="Enter email">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Account</label>
                            <input type="text" class="form-control" id="account" placeholder="account">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Full Name</label>
                            <input type="text" class="form-control" id="fullName" placeholder="fullName">
                        </div>

                        <div class="form-group">
                            <label for="tenantPassword">Mật khẩu <span style="color: red;">*</span></label>
                            <input type="password" id="tenantPassword" name="ha_password" value="{{ old('ha_password') }}"
                                class="form-control @error('ha_password') is-invalid @enderror">
                            @error('ha_password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('ha_password') }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-check">
                            <input type="checkbox" id="showPassword" class="form-check-input">
                            <label class="form-check-label" for="showPassword">Hiện mật khẩu</label>
                        </div>
                    </form>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                    <button type="button" class="btn btn-primary" onclick="submitAddByForm()">Submit</button>
                </div>
            </div>
            <!-- /.card -->
        </div>

        <div class="col-md-6">
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">By Excel, CSV File</h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="exampleInputFile">File input</label>
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="exampleInputFile">
                                <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                            </div>
                            <div class="input-group-append">
                                <span class="input-group-text">Upload</span>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                    <button type="button" class="btn btn-primary" onclick="submitAddByForm()">Submit</button>
                </div>
            </div>
            <!-- /.card -->
        </div>
        <div class="col-md-12" id="bulk-insert-element">
            <bulk-insert-users
                submit-url="{{ route('client.users.store', ['tenant_id' => auth()->user()->tenant_id]) }}"></bulk-insert-users>
            <!-- /.card -->
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
            const passwordInput = document.getElementById('tenantPassword');
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
