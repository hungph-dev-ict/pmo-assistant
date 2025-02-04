@extends('layouts.app')

@section('inline_css')
    {{-- <link rel="stylesheet" href="{{ asset('build/css/plugins/sweetalert2-theme-bootstrap-4-css.css') }}"> --}}
    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.15.10/dist/sweetalert2.min.css"> --}}
    <link rel="stylesheet" href="{{ asset('build/css/plugins/toastr-css.css') }}">
    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.css"> --}}
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
                <h3 class="card-title">Projects</h3>

                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped projects">
                    <thead>
                        <tr>
                            <th style="width: 1%">
                                #
                            </th>
                            <th style="width: 20%">
                                Project Name
                            </th>
                            <th style="width: 30%">
                                Description
                            </th>
                            <th>
                                Start Date
                            </th>
                            <th style="width: 8%" class="text-center">
                                End Date
                            </th>
                            <th style="width: 20%">
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($projects as $project)
                            <tr>
                                <td>
                                    {{ $project->id }}
                                </td>
                                <td>
                                    <a>
                                        {{ $project->name }}
                                    </a>
                                    <br />
                                    <small>
                                        {{ $project->description }}
                                    </small>
                                </td>
                                <td>
                                    {{ $project->description }}
                                </td>
                                <td>
                                    {{ $project->start_date }}
                                </td>
                                <td class="project-state">
                                    {{ $project->end_date }}
                                </td>
                                <td class="project-actions text-right">
                                    <a class="btn btn-primary btn-sm" href="{{ route('projects.show', $project->id) }}">
                                        <i class="fas fa-folder">
                                        </i>
                                        View
                                    </a>
                                    <!-- <a class="btn btn-info btn-sm" href="#">
                                                                        <i class="fas fa-pencil-alt">
                                                                        </i>
                                                                        Edit
                                                                    </a> -->
                                    <a class="btn btn-danger btn-sm" href="#">
                                        <i class="fas fa-trash">
                                        </i>
                                        Delete
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    {{-- {{$projects->links()}} --}}
                </table>
            </div>
            <!-- /.card-body -->
            <div class="card-footer d-flex justify-content-between align-items-center" style="padding: 0.5rem 1rem;">
                <div class="d-flex">
                    Showing
                    <strong class="mx-1">{{ $projects->firstItem() }}</strong>
                    to
                    <strong class="mx-1">{{ $projects->lastItem() }}</strong>
                    of
                    <strong class="mx-1">{{ $projects->total() }}</strong>
                    entries
                </div>
                <div class="pagination-wrapper ml-auto">
                    {{ $projects->links('vendor.pagination.default') }}
                </div>
            </div>
        </div>
        <!-- /.card -->
    </section>
    <!-- /.content -->
@endsection

@section('inline_js')
    {{-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.15.10/dist/sweetalert2.all.min.js"></script> --}}
    {{-- <script src="{{ asset('build/js/plugins/sweetalert2-js.js') }}"></script> --}}
    {{-- <script src="https://cdn.jsdelivr.net/npm/toastr@2.1.4/toastr.min.js"></script> --}}
    <script src="{{ asset('build/js/plugins/toastr-js.js') }}"></script>
@endsection

@section('inline_js_custom')
    <script>
        @if (session('success'))
            $(function() {
                toastr.success('{{ session('success') }}', 'Project Created');
            });
        @endif
    </script>
@endsection
