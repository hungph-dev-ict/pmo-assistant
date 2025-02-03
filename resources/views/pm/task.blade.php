@extends('layouts.app')

@section('page_title')
    Projects
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Projects</li>
@endsection

@section('inline_css')
    <link rel="stylesheet" href="{{ asset('build/css/plugins/select2-min-css.css') }}">
    <link rel="stylesheet" href="{{ asset('build/css/plugins/select2-bootstrap4-css.css') }}">
@endsection

@section('content')
        <!-- Default box -->
        <div class="card card-success collapsed-card">
            <div class="card-header">
                <h3 class="card-title">Add new task</h3>
                <a class="btn btn-success btn-sm float-right" data-toggle="modal" data-target="#modal-default">
                    <i class="fas fa-plus">
                    </i>
                    Add new Task
                </a>
            </div>
            <div class="modal fade" id="modal-default">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Default Modal</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="exampleInputBorder">Bottom Border only <code>.form-control-border</code></label>
                                <input type="text" class="form-control form-control-border" id="exampleInputBorder" placeholder=".form-control-border">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputBorderWidth2">Bottom Border only 2px Border <code>.form-control-border.border-width-2</code></label>
                                <input type="text" class="form-control form-control-border border-width-2" id="exampleInputBorderWidth2" placeholder=".form-control-border.border-width-2">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputRounded0">Flat <code>.rounded-0</code></label>
                                <input type="text" class="form-control rounded-0" id="exampleInputRounded0" placeholder=".rounded-0">
                            </div>
                            <h4>Custom Select</h4>
                            <div class="form-group">
                                <label for="exampleSelectBorder">Bottom Border only <code>.form-control-border</code></label>
                                <select class="custom-select form-control-border" id="exampleSelectBorder">
                                    <option>Value 1</option>
                                    <option>Value 2</option>
                                    <option>Value 3</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="exampleSelectBorderWidth2">Bottom Border only <code>.form-control-border.border-width-2</code></label>
                                <select class="custom-select form-control-border border-width-2" id="exampleSelectBorderWidth2">
                                    <option>Value 1</option>
                                    <option>Value 2</option>
                                    <option>Value 3</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="exampleSelectRounded0">Flat <code>.rounded-0</code></label>
                                <select class="custom-select rounded-0" id="exampleSelectRounded0">
                                    <option>Value 1</option>
                                    <option>Value 2</option>
                                    <option>Value 3</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary">Save changes</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
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
                                    <img src="{{ Vite::asset('resources/images/adminlte/user2-160x160.jpg') }}" alt="Avatar" class="img-circle elevation-2" style="width: 25px; height: 25px; object-fit: cover; margin-right: 10px;">
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

        <!-- Default box -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Task List</h3>
                <a class="btn btn-success btn-sm float-right" data-toggle="modal" data-target="#modal-xl">
                    <i class="fas fa-plus">
                    </i>
                    Add new Task
                </a>
            </div>
            <!-- /.modal -->
            <!-- /.card-header -->
            <div class="card-body p-0">

                <div class="modal-body">
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
                                <label for="exampleSelectBorder">PIC</label>
                                <select class="form-control select2 @error('project_project_manager') is-invalid @enderror"
                                    style="width: 100%;>
                                        @foreach ($users as $user)
<option value="{{ $user->id }}">{{ $user->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Plan Start Date</label>
                                <div class="input-group date" id="reservationdate" data-target-input="nearest">
                                    <input type="text" class="form-control datetimepicker-input"
                                        data-target="#reservationdate" />
                                    <div class="input-group-append" data-target="#reservationdate"
                                        data-toggle="datetimepicker">
                                        <div class="input-group-text">
                                            <svg class="svg-inline--fa fa-calendar fa-w-14" aria-hidden="true"
                                                focusable="false" data-prefix="fa" data-icon="calendar" role="img"
                                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" data-fa-i2svg="">
                                                <path fill="currentColor"
                                                    d="M112 192h224c8.8 0 16 7.2 16 16v224c0 8.8-7.2 16-16 16H112c-8.8 0-16-7.2-16-16V208c0-8.8 7.2-16 16-16zm192 32h-64v64h64v-64zM96 64h16V40c0-13.2 10.8-24 24-24s24 10.8 24 24v24h128V40c0-13.2 10.8-24 24-24s24 10.8 24 24v24h16c35.3 0 64 28.7 64 64v352c0 35.3-28.7 64-64 64H96c-35.3 0-64-28.7-64-64V128c0-35.3 28.7-64 64-64zm-48 96h352V128c0-8.8-7.2-16-16-16H64c-8.8 0-16 7.2-16 16v32z">
                                                </path>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Plan End Date</label>
                                <div class="input-group date" id="reservationdate" data-target-input="nearest">
                                    <input type="text" class="form-control datetimepicker-input"
                                        data-target="#reservationdate" />
                                    <div class="input-group-append" data-target="#reservationdate"
                                        data-toggle="datetimepicker">
                                        <div class="input-group-text">
                                            <svg class="svg-inline--fa fa-calendar fa-w-14" aria-hidden="true"
                                                focusable="false" data-prefix="fa" data-icon="calendar" role="img"
                                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" data-fa-i2svg="">
                                                <path fill="currentColor"
                                                    d="M112 192h224c8.8 0 16 7.2 16 16v224c0 8.8-7.2 16-16 16H112c-8.8 0-16-7.2-16-16V208c0-8.8 7.2-16 16-16zm192 32h-64v64h64v-64zM96 64h16V40c0-13.2 10.8-24 24-24s24 10.8 24 24v24h128V40c0-13.2 10.8-24 24-24s24 10.8 24 24v24h16c35.3 0 64 28.7 64 64v352c0 35.3-28.7 64-64 64H96c-35.3 0-64-28.7-64-64V128c0-35.3 28.7-64 64-64zm-48 96h352V128c0-8.8-7.2-16-16-16H64c-8.8 0-16 7.2-16 16v32z">
                                                </path>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Actual Start Date</label>
                                <div class="input-group date" id="reservationdate" data-target-input="nearest">
                                    <input type="text" class="form-control datetimepicker-input"
                                        data-target="#reservationdate" />
                                    <div class="input-group-append" data-target="#reservationdate"
                                        data-toggle="datetimepicker">
                                        <div class="input-group-text">
                                            <svg class="svg-inline--fa fa-calendar fa-w-14" aria-hidden="true"
                                                focusable="false" data-prefix="fa" data-icon="calendar" role="img"
                                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"
                                                data-fa-i2svg="">
                                                <path fill="currentColor"
                                                    d="M112 192h224c8.8 0 16 7.2 16 16v224c0 8.8-7.2 16-16 16H112c-8.8 0-16-7.2-16-16V208c0-8.8 7.2-16 16-16zm192 32h-64v64h64v-64zM96 64h16V40c0-13.2 10.8-24 24-24s24 10.8 24 24v24h128V40c0-13.2 10.8-24 24-24s24 10.8 24 24v24h16c35.3 0 64 28.7 64 64v352c0 35.3-28.7 64-64 64H96c-35.3 0-64-28.7-64-64V128c0-35.3 28.7-64 64-64zm-48 96h352V128c0-8.8-7.2-16-16-16H64c-8.8 0-16 7.2-16 16v32z">
                                                </path>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Actual End Date</label>
                                <div class="input-group date" id="reservationdate" data-target-input="nearest">
                                    <input type="text" class="form-control datetimepicker-input"
                                        data-target="#reservationdate" />
                                    <div class="input-group-append" data-target="#reservationdate"
                                        data-toggle="datetimepicker">
                                        <div class="input-group-text">
                                            <svg class="svg-inline--fa fa-calendar fa-w-14" aria-hidden="true"
                                                focusable="false" data-prefix="fa" data-icon="calendar" role="img"
                                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"
                                                data-fa-i2svg="">
                                                <path fill="currentColor"
                                                    d="M112 192h224c8.8 0 16 7.2 16 16v224c0 8.8-7.2 16-16 16H112c-8.8 0-16-7.2-16-16V208c0-8.8 7.2-16 16-16zm192 32h-64v64h64v-64zM96 64h16V40c0-13.2 10.8-24 24-24s24 10.8 24 24v24h128V40c0-13.2 10.8-24 24-24s24 10.8 24 24v24h16c35.3 0 64 28.7 64 64v352c0 35.3-28.7 64-64 64H96c-35.3 0-64-28.7-64-64V128c0-35.3 28.7-64 64-64zm-48 96h352V128c0-8.8-7.2-16-16-16H64c-8.8 0-16 7.2-16 16v32z">
                                                </path>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary">Create</button>
                        </div>
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
@endsection

@section('inline_js_custom')
    <script>
        $(function() {
            //Initialize Select2 Elements
            $('.select2').select2({
                placeholder: "-- Select Project Manager --", // Placeholder hiển thị khi không có lựa chọn
                allowClear: true // Bật tính năng xóa lựa chọn
            })
        });
    </script>
@endsection
