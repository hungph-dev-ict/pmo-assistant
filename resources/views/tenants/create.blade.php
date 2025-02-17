@extends('layouts.app')

@section('page_title')
    {{ __('labels.tenant_add') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('tenants.index') }}">{{ __('labels.tenants') }}</a></li>
    <li class="breadcrumb-item active">{{ __('labels.tenant_add') }}</li>
@endsection

@section('inline_css')
    <style>
        /* Custom style for error messages */
        .custom-invalid-feedback {
            display: block;
            /* Ensure it is displayed */
            color: #dc3545;
            /* Red color to match standard error messages */
            font-size: 80%;
            /* Slightly smaller text */
            margin-top: 0.25rem;
            /* Add some spacing */
        }

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
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">{{ __('labels.general') }}</h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('tenants.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="tenantName">{{ __('labels.tenant_name') }}<span style="color: red;">*</span></label>
                            <input type="text" id="tenantName" name="tenant_name"
                                class="form-control @error('tenant_name') is-invalid @enderror"
                                value="{{ old('tenant_name') }}">
                            @error('tenant_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('tenant_name') }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="tenantDescription">{{ __('labels.tenant_description') }}<span
                                    style="color: red;">*</span></label>
                            <textarea id="tenantDescription" name="tenant_description"
                                class="form-control @error('tenant_description') is-invalid @enderror" rows="3">{{ old('tenant_description') }}</textarea>
                            @error('tenant_description')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('tenant_description') }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="tenant_logo">{{ __('labels.tenant_logo') }}</label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" name="tenant_logo"
                                        class="custom-file-input @error('tenant_logo') is-invalid @enderror"
                                        id="tenantLogo">
                                    <label class="custom-file-label"
                                        for="tenantLogo">{{ __('labels.choose_file') }}</label>
                                </div>

                            </div>
                            <!-- Thẻ img để hiển thị ảnh xem trước -->
                            <div class="mt-3">
                                <img id="previewTenantLogo" src="#" alt="PreviewLogo" class="img-fluid img-thumbnail"
                                    style="max-width: 200px; display: none;">
                            </div>
                            @error('tenant_logo')
                                <span class="custom-invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('tenant_logo') }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="tenantPlan">{{ __('labels.tenant_plan') }}<span
                                    style="color: red;">*</span></label>
                            <select id="tenantPlan" name="tenant_plan"
                                class="form-control custom-select @error('tenant_plan') is-invalid @enderror">
                                <option selected disabled>{{ __('labels.tenant_select_plan') }}</option>
                                @foreach ($plans as $plan)
                                    <option value="{{ $plan->id }}"
                                        {{ old('tenant_plan') == $plan->id ? 'selected' : '' }}>
                                        {{ $plan->name . ' - ' . $plan->price . '$ per user' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('tenant_plan')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('tenant_plan') }}</strong>
                                </span>
                            @enderror
                        </div>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
        <div class="col-md-6">
            <div class="card card-secondary">
                <div class="card-header">
                    <h3 class="card-title">{{ __('labels.tenant_head_account') }}</h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="haEmail">{{ __('labels.tenant_mail') }}<span style="color: red;">*</span></label>
                        <input type="text" id="haEmail" name="ha_email"
                            class="form-control @error('ha_email') is-invalid @enderror" value="{{ old('ha_email') }}">
                        @error('ha_email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('ha_email') }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="haAccount">{{ __('labels.tenant_account') }}<span
                                style="color: red;">*</span></label>
                        <input type="text" id="haAccount" name="ha_account"
                            class="form-control @error('ha_account') is-invalid @enderror"
                            value="{{ old('ha_account') }}">
                        @error('ha_account')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('ha_account') }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="haFullName">{{ __('labels.tenant_full_name') }}<span
                                style="color: red;">*</span></label>
                        <input type="text" id="haFullName" name="ha_full_name"
                            class="form-control @error('ha_full_name') is-invalid @enderror"
                            value="{{ old('ha_full_name') }}">
                        @error('ha_full_name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('ha_full_name') }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="ha_avatar">{{ __('labels.tenant_upload_avatar') }}</label>
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file" name="ha_avatar"
                                    class="custom-file-input @error('ha_avatar') is-invalid @enderror" id=" haAvatar">
                                <label class="custom-file-label" for="haAvatar">{{ __('labels.choose_file') }}</label>

                            </div>
                        </div>
                        <!-- Thẻ img để hiển thị ảnh xem trước -->
                        <div class="mt-3">
                            <img id="previewHaAvatar" src="#" alt="PreviewAvatar" class="img-fluid img-thumbnail"
                                style="max-width: 200px; display: none;">
                        </div>
                        @error('ha_avatar')
                            <span class="custom-invalid-feedback" role="alert">
                                <strong>{{ $errors->first('ha_avatar') }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="tenantPassword">{{ __('labels.tenant_password') }}<span
                                style="color: red;">*</span></label>
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
                        <label class="form-check-label" for="showPassword">{{ __('labels.show_password') }}</label>
                    </div>
                </div>
                <!-- /.card -->
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <a href="#" class="btn btn-secondary">{{ __('labels.cancel') }}</a>
            <input type="submit" value="{{ __('labels.tenant_create_new_tenant') }}"
                class="btn btn-success float-right">
        </div>
    </div>
    </form>
@endsection

@section('inline_js')
    @vite(['resources/js/plugins/bs-custom-file-input/bs-custom-file-input.min.js'])
@endsection

@section('custom_inline_js')
    <script>
        $(function() {
            bsCustomFileInput.init();
        });

        function generateRandomString(length = 12) {
            const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
            let result = '';
            const charactersLength = characters.length;
            for (let i = 0; i < length; i++) {
                result += characters.charAt(Math.floor(Math.random() * charactersLength));
            }
            return result;
        }

        $(document).on('change', '#tenantLogo', function(event) {
            const inputFile = event.target.files[0]; // Lấy file được chọn
            const previewTenantLogo = $('#previewTenantLogo'); // Thẻ img để xem trước

            if (inputFile) {
                const reader = new FileReader(); // Tạo FileReader để đọc file
                reader.onload = function(e) {
                    previewTenantLogo.attr('src', e.target.result); // Gán URL hình ảnh vào thẻ img
                    previewTenantLogo.show(); // Hiển thị ảnh
                };
                reader.readAsDataURL(inputFile); // Đọc file dưới dạng URL
            } else {
                previewTenantLogo.hide(); // Nếu không có file, ẩn ảnh xem trước
            }
        });

        $(document).on('change', '#haAvatar', function(event) {
            const inputFile = event.target.files[0]; // Lấy file được chọn
            const previewHaAvatar = $('#previewHaAvatar'); // Thẻ img để xem trước

            if (inputFile) {
                const reader = new FileReader(); // Tạo FileReader để đọc file
                reader.onload = function(e) {
                    previewHaAvatar.attr('src', e.target.result); // Gán URL hình ảnh vào thẻ img
                    previewHaAvatar.show(); // Hiển thị ảnh
                };
                reader.readAsDataURL(inputFile); // Đọc file dưới dạng URL
            } else {
                previewHaAvatar.hide(); // Nếu không có file, ẩn ảnh xem trước
            }
        });
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
