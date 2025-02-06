@extends('layouts.app')

@section('page_title')
    Task List
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active"><a href="{{ route('projects.index') }}">Tasks</a></li>
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
            <form id="TaskForm" action="{{ route('pm.tasks.store', $project_id) }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="exampleInputBorder">Parent Task</label>
                            <input type="text" name="description" class="form-control form-control-border"
                                id="exampleInputBorder" placeholder="">
                        </div>

                        <div class="form-group">
                            <label for="exampleInputBorderWidth2">Task</label>
                            <input type="text" name ="name" class="form-control form-control-border border-width-2"
                                id="exampleInputBorderWidth2" placeholder="">
                        </div>

                        <div class="form-group">
                            <label for="assignee">Assignee</label>
                            <select id="assignee" name="assignee"
                                class="form-control select2 @error('assignee') is-invalid @enderror" style="width: 100%;">
                                <option value="">Assignee</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}"
                                        {{ old('assignee') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('assignee')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="task_type">Task Type</label>
                            <select id="task_type" name="task_type" class="form-control">
                                <option value="0">Epic</option>
                                <option value="1">Task</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputBorderWidth2">Estimate Effort (MD)</label>
                            <input type="number" name="estimate_effort"
                                class="form-control form-control-border border-width-2" id="exampleInputBorderWidth2"
                                placeholder="" step="1"
                                min="0"oninput="this.value = this.value.replace(/[^0-9]/g, '');">

                        </div>
                        <div class="form-group">
                            <label for="exampleInputBorderWidth2">Actual Effort (MD)</label>
                            <input type="number" name="actual_effort"
                                class="form-control form-control-border border-width-2" id="exampleInputBorderWidth2"
                                placeholder="" step="1"
                                min="0"oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                        </div>
                        <div class="form-group">
                            <label for="progress">Progress</label>
                            <input type="number" name="progress" class="form-control" min="0" max="100"
                                step="1">

                        </div>

                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="planStartDate">Plan Start Date</label>
                            <div class="input-group date" id="planStartDatePicker" data-target-input="nearest">
                                <input type="date" id="planStartDate" name="plan_start_date"
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
                                <input type="date" id="planEndDate" name="plan_end_date"
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
                                <input type="date" id="actualStartDate" name="actual_start_date"
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
                                <input type="date" id="actualEndDate" name="actual_end_date"
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
                    <div class="card-footer" style="border-top: none;">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" onclick="submitTaskForm()">Create</button>
                    </div>
            </form>
        </div>
        <!-- /.card-body -->
    </div>
    <div id="task-app">
        <task-container project-id="{{ $project_id }}"></task-container>
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

                at: 'YYYY-MM-DD'
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

            function submitTaskForm() {
                document.getElementById('TaskForm').submit();
            }
            document.getElementById('type').addEventListener('change', function () {
        var parentTaskGroup = document.getElementById('parentTaskGroup');
        if (this.value == "1") {
            parentTaskGroup.style.display = "block";
        } else {
            parentTaskGroup.style.display = "none";
            document.getElementById('parent_id').value = "";
        }
    });

        });
    </script>
@endsection
