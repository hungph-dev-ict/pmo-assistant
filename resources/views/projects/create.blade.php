@extends('layouts.app')

@section('inline_css')
<!-- Tempus Dominus CSS -->
<link rel="stylesheet" href="{{ asset('build/css/plugins/tempusdominus-bootstrap-4-css.css') }}">
<link rel="stylesheet" href="{{ asset('build/css/plugins/select2-min-css.css') }}">
<link rel="stylesheet" href="{{ asset('build/css/plugins/select2-bootstrap4-css.css') }}">
@endsection

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Project Add</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Project Add</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<form action="{{ route('projects.store') }}" method="POST">
    @csrf
    <!-- Main content -->
    <section class="content">
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
                        <div class="form-group">
                            <label for="inputName">Project Name</label>
                            <input type="text" id="inputName" name="name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="inputDescription">Project Description</label>
                            <textarea id="inputDescription" name="description" class="form-control" rows="4"
                                required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="inputStatus">Status</label>
                            <select id="inputStatus" name="status" class="form-control custom-select" required>
                                <option selected disabled>Select one</option>
                                @foreach($projects_status as $status)
                                <option value="{{ $status->id }}">{{ $status->value1 }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="inputClientCompany">Client Company</label>
                            <input type="text" id="inputClientCompany" name="client_company" class="form-control"
                                required>
                        </div>
                        <div class="form-group">
                            <label>Project Manager</label>
                            <select id="inputProjectManager" name="project_manager" class="form-control select2"
                                required style="width: 100%;">
                                <option value="" disabled selected>-- Select Project Manager --</option>
                                @foreach($users_pmmanager as $pmmanager)
                                <option value="{{ $pmmanager->id }}">{{ $pmmanager->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <div class="col-md-6">
                <div class="card card-secondary">
                    <div class="card-header">
                        <h3 class="card-title">Time & Budget</h3>

                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Date -->
                        <div class="form-group">
                            <label>Start Date:</label>
                            <div class="input-group date" id="startdate" data-target-input="nearest">
                                <input type="text" class="form-control datetimepicker-input" data-target="#startdate"
                                    name="start_date" required />
                                <div class="input-group-append" data-target="#startdate" data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                </div>
                            </div>
                        </div>
                        <!-- Date -->
                        <div class="form-group">
                            <label>End Date:</label>
                            <div class="input-group date" id="enddate" data-target-input="nearest">
                                <input type="text" class="form-control datetimepicker-input" data-target="#enddate"
                                    name="end_date" required />
                                <div class="input-group-append" data-target="#enddate" data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputEstimatedBudget">Estimated budget (MM)</label>
                            <input type="number" id="inputEstimatedBudget" name="estimated_budget" class="form-control"
                                required>
                        </div>
                        <div class="form-group">
                            <label for="inputSpentBudget">Total amount spent (MM)</label>
                            <input type="number" id="inputSpentBudget" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="inputEstimatedDuration">Estimated project duration (months)</label>
                            <input type="number" id="inputEstimatedDuration" name="estimated_project_duration"
                                class="form-control" required>
                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <a href="#" class="btn btn-secondary">Cancel</a>
                <input type="submit" value="Create new Project" class="btn btn-success float-right">
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

@section('inline_js_custom')
<script>
$(function() {
    //Initialize Select2 Elements
    $('.select2').select2({
        placeholder: "-- Select Project Manager --", // Placeholder hiển thị khi không có lựa chọn
        allowClear: true // Bật tính năng xóa lựa chọn
    })

    //Date picker
    $('#startdate').datetimepicker({
        format: 'YYYY-MM-DD', // Định dạng ngày
        useCurrent: false,
    });

    // Khởi tạo datetimepicker cho reservationdate
    $('#enddate').datetimepicker({
        format: 'YYYY-MM-DD',
        useCurrent: false,
    });

    // Ràng buộc khi thay đổi Start Date
    $('#startdate').on('change.datetimepicker', function(e) {
        // Cập nhật minDate cho End Date khi Start Date thay đổi
        $('#enddate').datetimepicker('minDate', e.date);
    });

    // Ràng buộc khi thay đổi End Date
    $('#enddate').on('change.datetimepicker', function(e) {
        // Cập nhật maxDate cho Start Date khi End Date thay đổi
        $('#startdate').datetimepicker('maxDate', e.date);
    });

})
</script>
@endsection