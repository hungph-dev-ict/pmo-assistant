@extends('layouts.app')

@section('page_title')
    {{ __('labels.edit') }} {{ $project->name }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('projects.index') }}">{{ __('labels.projects') }}</a></li>
    <li class="breadcrumb-item active">{{ $project->name }}</li>
@endsection

@section('inline_css')
    <!-- Tempus Dominus CSS -->
    <link rel="stylesheet" href="{{ asset('build/css/plugins/tempusdominus-bootstrap-4-css.css') }}">
    <link rel="stylesheet" href="{{ asset('build/css/plugins/select2-min-css.css') }}">
    <link rel="stylesheet" href="{{ asset('build/css/plugins/select2-bootstrap4-css.css') }}">
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
    <form action="{{ route('projects.update', $project->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <!-- Main content -->
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
                        <div class="form-group">
                            <label for="projectName">{{ __('labels.project_name') }}<span style="color: red;">*</span></label>
                            <input type="text" id="projectName" name="project_name"
                                class="form-control @error('project_name') is-invalid @enderror"
                                value="{{ old('project_name', $project->name) }}">
                            @error('project_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('project_name') }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="projectDescription">{{ __('labels.project_description') }}</label>
                            <textarea id="projectDescription" name="project_description"
                                class="form-control @error('project_description') is-invalid @enderror" rows="3">{{ old('project_description', $project->description) }}</textarea>
                            @error('project_description')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('project_description') }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="projectStatus">{{ __('labels.project_status') }}<span style="color: red;">*</span></label>
                            <select id="projectStatus" name="project_status"
                                class="form-control custom-select @error('project_status') is-invalid @enderror">
                                <option selected disabled>{{ __('labels.project_select_status') }}</option>
                                @foreach ($projectStatuses as $projectStatus)
                                    <option value="{{ $projectStatus->key }}"
                                        {{ old('project_status', $project->status) == $projectStatus->key ? 'selected' : '' }}>
                                        {{ $projectStatus->value1 }}
                                    </option>
                                @endforeach
                            </select>
                            @error('project_status')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('project_status') }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="projectClientCompany">{{ __('labels.project_client_company') }}</label>
                            <input type="text" id="projectClientCompany" name="project_client_company"
                                class="form-control @error('project_client_company') is-invalid @enderror"
                                value="{{ old('project_client_company', $project->client_company) }}">
                            @error('project_client_company')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('project_client_company') }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="projectProjectManager">{{ __('labels.project_manager') }}<span style="color: red;">*</span></label>
                            <select id="projectProjectManager" name="project_project_manager"
                                class="form-control select2 @error('project_project_manager') is-invalid @enderror"
                                style="width: 100%;">
                                <option value="" disabled selected>{{ __('labels.project_select_manager') }}</option>
                                @foreach ($listProjectManager as $projectManager)
                                    <option value="{{ $projectManager->id }}"
                                        {{ old('project_project_manager', $project->project_manager) == $projectManager->id ? 'selected' : '' }}>
                                        {{ $projectManager->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('project_project_manager')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('project_project_manager') }}</strong>
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
                        <h3 class="card-title">{{ __('labels.time_budget') }}</h3>

                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Date -->
                        <div class="form-group">
                            <label for="projectStartDate">{{ __('labels.project_start_date') }}</label>
                            <div class="input-group date" id="projectStartDatePicker" data-target-input="nearest">
                                <input type="text" id="projectStartDate" name="project_start_date"
                                    class="form-control datetimepicker-input @error('project_start_date') is-invalid @enderror"
                                    data-target="#projectStartDatePicker"
                                    value="{{ old('project_start_date', $project->start_date) }}" />
                                <div class="input-group-append" data-target="#projectStartDatePicker"
                                    data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                </div>
                                @error('project_start_date')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('project_start_date') }}</strong>
                                    </span>
                                @enderror
                            </div>

                        </div>
                        <!-- Date -->
                        <div class="form-group">
                            <label for="projectEndDate">{{ __('labels.project_end_date') }}</label>
                            <div class="input-group date" id="projectEndDatePicker" data-target-input="nearest">
                                <input type="text" id="projectEndDate" name="project_end_date"
                                    class="form-control datetimepicker-input @error('project_end_date') is-invalid @enderror"
                                    data-target="#projectEndDatePicker"
                                    value="{{ old('project_end_dates', $project->end_date) }}" />
                                <div class="input-group-append" data-target="#projectEndDatePicker"
                                    data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                </div>
                                @error('project_end_date')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('project_end_date') }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="projectEstimatedBudget">{{ __('labels.project_estimated_budget') }}</label>
                            <input type="text" id="projectEstimatedBudget" name="project_estimated_budget"
                                class="form-control @error('project_estimated_budget') is-invalid @enderror"
                                value="{{ old('project_estimated_budget', $project->estimated_budget) }}">
                            @error('project_estimated_budget')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('project_estimated_budget') }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="inputSpentBudget">{{ __('labels.project_total_amount_spent') }}</label>
                            <input type="number" id="inputSpentBudget" name="total_amount_spent" class="form-control"
                                value="{{ old('total_amount_spent') }}">
                        </div>
                        <div class="form-group">
                            <label
                                for="projectEstimatedProjectDuration">{{ __('labels.project_estimated_project_duration') }}</label>
                            <input type="text" id="projectEstimatedProjectDuration"
                                name="project_estimated_project_duration"
                                class="form-control @error('project_estimated_project_duration') is-invalid @enderror"
                                value="{{ old('project_estimated_project_duration', $project->estimated_project_duration) }}">
                            @error('project_estimated_project_duration')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('project_estimated_project_duration') }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->

            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <a href="{{ route('projects.index') }}" class="btn btn-secondary float-right">{{ __('labels.cancel') }}</a>
                <input type="submit" value="{{ __('labels.update') }}" class="btn btn-success">
            </div>
        </div>
        </section>
        <!-- /.content -->
    </form>
@endsection

@section('inline_js')
    <!-- Select2 -->
    <script src="{{ asset('build/js/plugins/select2-full-js.js') }}"></script>
    <!-- Moment.js from CDN -->
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
    {{-- <script src="{{ asset('build/js/plugins/moment-js.js') }}"></script> --}}
    <!-- Tempus Dominus JS -->
    <script src="{{ asset('build/js/plugins/tempusdominus-bootstrap-4-js.js') }}"></script>
@endsection

@section('custom_inline_js')
    <script>
        $(function() {
            //Initialize Select2 Elements
            $('.select2').select2({
                placeholder: '{{ __('labels.project_select_manager') }}', // Placeholder hiển thị khi không có lựa chọn
                allowClear: true // Bật tính năng xóa lựa chọn
            })

            //Date picker
            $('#projectStartDatePicker').datetimepicker({
                format: 'YYYY-MM-DD', // Định dạng ngày
                useCurrent: false,
            });
            // Khởi tạo datetimepicker cho reservationdate
            $('#projectEndDatePicker').datetimepicker({
                format: 'YYYY-MM-DD',
                useCurrent: false,
            });
            // Ràng buộc khi thay đổi Start Date
            $('#projectStartDatePicker').on('change.datetimepicker', function(e) {
                // Cập nhật minDate cho End Date khi Start Date thay đổi
                $('#projectEndDatePicker').datetimepicker('minDate', e.date);
            });
            // Ràng buộc khi thay đổi End Date
            $('#projectEndDatePicker').on('change.datetimepicker', function(e) {
                // Cập nhật maxDate cho Start Date khi End Date thay đổi
                $('#projectStartDatePicker').datetimepicker('maxDate', e.date);
            })
        });

        $(document).ready(function() {
            // Kiểm tra lỗi và thêm class vào phần tử Select2
            if ($('#projectProjectManager').hasClass('is-invalid')) {
                $('#projectProjectManager').next('.select2-container').find('.select2-selection')
                    .addClass(
                        'is-invalid');
            }
        });
    </script>
@endsection
