@extends('layouts.app')

@section('title', 'Custom Page')

@section('inline_css')

@endsection

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Projects</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Projects</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Task List</h3>
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
    </section>
@endsection

@section('inline_js')

@endsection
