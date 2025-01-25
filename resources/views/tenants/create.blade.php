@extends('layouts.app')

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
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">General</h3>

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
                            <label for="tenantName">Tenant Name <span style="color: red;">*</span></label>
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
                            <label for="tenantDescription">Tenant Description <span style="color: red;">*</span></label>
                            <textarea id="tenantDescription" name="tenant_description"
                                class="form-control @error('tenant_description') is-invalid @enderror" rows="3">{{ old('tenant_description') }}</textarea>
                            @error('tenant_description')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('tenant_description') }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="tenant_logo">Tenant Logo</label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" name="tenant_logo"
                                        class="custom-file-input @error('tenant_logo') is-invalid @enderror"
                                        id="tenantLogo">
                                    <label class="custom-file-label" for="tenantLogo">Choose file</label>
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
                            <label for="tenantPlan">Plan <span style="color: red;">*</span></label>
                            <select id="tenantPlan" name="tenant_plan"
                                class="form-control custom-select @error('tenant_plan') is-invalid @enderror">
                                <option selected disabled>Select one</option>
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
                    <h3 class="card-title">Head Account</h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="haEmail">Email <span style="color: red;">*</span></label>
                        <input type="text" id="haEmail" name="ha_email"
                            class="form-control @error('ha_email') is-invalid @enderror" value="{{ old('ha_email') }}">
                        @error('ha_email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('ha_email') }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="haAccount">Account <span style="color: red;">*</span></label>
                        <input type="text" id="haAccount" name="ha_account"
                            class="form-control @error('ha_account') is-invalid @enderror" value="{{ old('ha_account') }}">
                        @error('ha_account')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('ha_account') }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="haFullName">Full Name <span style="color: red;">*</span></label>
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
                        <label for="ha_avatar">Upload Avatar</label>
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file" name="ha_avatar"
                                    class="custom-file-input @error('ha_avatar') is-invalid @enderror"" id="haAvatar">
                                <label class="custom-file-label" for="haAvatar">Choose file</label>

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
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <a href="#" class="btn btn-secondary">Cancel</a>
            <input type="submit" value="Create new Tenant" class="btn btn-success float-right">
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
@endsection
