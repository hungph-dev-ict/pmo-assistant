@extends('layouts.app')

@section('page_title')
Projects
@endsection

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item active"><a href="{{ route('projects.index') }}">Projects</a></li>
@endsection

@section('inline_css')
<link rel="stylesheet" href="{{ asset('build/css/plugins/tempusdominus-bootstrap-4-css.css') }}">
<link rel="stylesheet" href="{{ asset('build/css/plugins/select2-min-css.css') }}">
<link rel="stylesheet" href="{{ asset('build/css/plugins/select2-bootstrap4-css.css') }}">
@endsection

@section('content')
<!-- Default box -->
<div class="card card-success collapsed-card">
    <div class="card-header">
        <h3 class="card-title">Add New Task</h3>
        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                <i class="fas fa-plus"></i>
            </button>
        </div>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="exampleInputBorder">Parent Task</label>
                    <input type="text" class="form-control form-control-border" id="exampleInputBorder"
                        placeholder="">
                </div>
                <div class="form-group">
                    <label for="exampleInputBorderWidth2">Sub Task</label>
                    <input type="text" class="form-control form-control-border border-width-2"
                        id="exampleInputBorderWidth2" placeholder="">
                </div>
                <div class="form-group">
                    <label for="projectProjectManager">Assignee</label>
                    <select id="projectProjectManager" name="project_project_manager"
                        class="form-control select2 @error('project_project_manager') is-invalid @enderror"
                        style="width: 100%;">
                        <option value="">Assignee</option>
                        @foreach ($users as $user)
                        <option value="{{ $user->id }}" @selected(old('project_project_manager')==$user->id)>
                            {{ $user->name }}
                        </option>
                        @endforeach
                    </select>
                    @error('project_project_manager')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="planStartDate">Plan Start Date</label>
                    <div class="input-group date" id="planStartDatePicker" data-target-input="nearest">
                        <input type="text" id="planStartDate" name="plan_start_date"
                            class="form-control datetimepicker-input @error('plan_start_date') is-invalid @enderror"
                            data-target="#planStartDatePicker" value="{{ old('plan_start_date') }}" />
                        <div class="input-group-append" data-target="#planStartDatePicker"
                            data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                        @error('plan_start_date')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('plan_start_date') }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
                <div class="form-group">
                    <label for="planEndDate">Plan End Date</label>
                    <div class="input-group date" id="planEndDatePicker" data-target-input="nearest">
                        <input type="text" id="planEndDate" name="plant_end_date"
                            class="form-control datetimepicker-input @error('plan_end_date') is-invalid @enderror"
                            data-target="#planEndDatePicker" value="{{ old('plan_end_dates') }}" />
                        <div class="input-group-append" data-target="#planEndDatePicker"
                            data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                        @error('plan_end_date')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('plan_end_date') }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
                <div class="form-group">
                    <label for="actualStartDate">Actual Start Date</label>
                    <div class="input-group date" id="actualStartDatePicker" data-target-input="nearest">
                        <input type="text" id="actualStartDate" name="actual_start_date"
                            class="form-control datetimepicker-input @error('actual_start_date') is-invalid @enderror"
                            data-target="#actualStartDatePicker" value="{{ old('actual_start_date') }}" />
                        <div class="input-group-append" data-target="#actualStartDatePicker"
                            data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                        @error('actual_start_date')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('actual_start_date') }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="actualEndDate">Actual End Date</label>
                    <div class="input-group date" id="actualEndDatePicker" data-target-input="nearest">
                        <input type="text" id="actualEndDate" name="actual_end_date"
                            class="form-control datetimepicker-input @error('actual_end_date') is-invalid @enderror"
                            data-target="#actualEndDatePicker" value="{{ old('actual_end_date') }}" />
                        <div class="input-group-append" data-target="#actualEndDatePicker"
                            data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                        @error('actual_end_date')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('actual_end_date') }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Create</button>
            </div>
        </div>
        <!-- /.card-body -->
    </div>
</div>

<!-- Default box -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">List Task</h3>
        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                <i class="fas fa-minus"></i>
            </button>
        </div>
    </div>
    <!-- /.modal -->
    <!-- /.card-header -->
    <div class="card-body p-0">
        <table class="table table-sm">
            <thead>
                <tr>
                    <th style="width:3%">#</th>
                    <th style="width: 16%">Task</th>
                    <th style="width: 26%">Sub-Task</th>
                    <th style="width: 8%">PIC</th>
                    <th style="width: 6.5%">Plan Start Date</th>
                    <th style="width: 6.5%">Plan End Date</th>
                    <th style="width: 6.5%">Actual Start Date</th>
                    <th style="width: 6.5%">Actual End Date</th>
                    <th style="width: 2%">Estimate Effort (MD)</th>
                    <th style="width: 2%">Actual Effort (MD)</th>
                    <th style="width: 2%">Progress</th>
                    <th style="width:3%"></th>
                    <th style="width: 12%">Action</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>4.</td>
                    <td>
                        Parent
                    </td>
                    <td>
                        Child
                    </td>
                    <td>
                        <div class="d-flex align-items-center">
                            <img src="{{ Vite::asset('resources/images/adminlte/user2-160x160.jpg') }}"
                                alt="Avatar" class="img-circle elevation-2"
                                style="width: 25px; height: 25px; object-fit: cover; margin-right: 10px;">
                            <span>HungPH5</span>
                        </div>
                    </td>
                    <td>
                        2025/01/01
                    </td>
                    <td>
                        2025/01/01
                    </td>
                    <td>
                        2025/01/01
                    </td>
                    <td>
                        2025/01/01
                    </td>
                    <td>
                        12
                    </td>
                    <td>
                        30
                    </td>
                    <td>
                        <div class="progress progress-xs progress-striped active mt-2">
                            <div class="progress-bar bg-success" style="width: 90%"></div>
                        </div>
                    </td>
                    <td><span class="badge bg-success">90%</span>
                    </td>
                    <td><a class="btn btn-info btn-sm" href="#">
                            <i class="fas fa-pencil-alt">
                            </i>
                            Edit
                        </a>
                        <a class="btn btn-danger btn-sm" data-toggle="modal" data-target="#modal-danger">
                            <i class="fas fa-trash"></i>
                            Delete
                        </a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <!-- /.card-body -->
</div>
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
            placeholder: "Choose an assignee", // Placeholder hiển thị khi không có lựa chọn
            allowClear: true // Bật tính năng xóa lựa chọn
        })
        $('#planStartDatePicker').datetimepicker({
            format: 'YYYY-MM-DD', // Định dạng ngày
            useCurrent: false,
        });
        // Khởi tạo datetimepicker cho reservationdate
        $('#planEndDatePicker').datetimepicker({
            format: 'YYYY-MM-DD',
            useCurrent: false,
        });
        $('#actualStartDatePicker').datetimepicker({
            format: 'YYYY-MM-DD'
        });
        $('#actualEndDatePicker').datetimepicker({
            format: 'YYYY-MM-DD'
        });
        // Ràng buộc khi thay đổi Start Date
        $('#planStartDatePicker').on('change.datetimepicker', function(e) {
            // Cập nhật minDate cho End Date khi Start Date thay đổi
            $('#planStartDatePicker').datetimepicker('minDate', e.date);
        });
        // Ràng buộc khi thay đổi End Date
        $('#actualStartDatePicker').on('change.datetimepicker', function(e) {
            // Cập nhật maxDate cho Start Date khi End Date thay đổi
            $('#actualStartDatePicker').datetimepicker('maxDate', e.date);
        });
        $('#actualEndDatePicker').on('change.datetimepicker', function(e) {
            // Cập nhật minDate cho End Date khi Start Date thay đổi
            $('#actualEndDatePicker').datetimepicker('minDate', e.date);
        });

    })
</script>
@endsection
