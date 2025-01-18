@extends('layouts.app')

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
                            Start_date
                        </th>
                        <th style="width: 8%" class="text-center">
                            End_date
                        </th>
                        <th style="width: 20%">
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($projects as $project)
                    <tr>
                        <td>
                            #
                        </td>
                        <td>
                            <a>
                                {{$project->name}}
                            </a>
                            <br />
                            <small>
                                {{$project->description}}
                            </small>
                        </td>
                        <td>
                            {{$project->description}}
                        </td>
                        <td>
                            {{$project->start_date}}
                        </td>
                        <td class="project-state">
                            {{$project->end_date}}
                        </td>
                        <td class="project-actions text-right">
                            <a class="btn btn-primary btn-sm" href="#">
                                <i class="fas fa-folder">
                                </i>
                                View
                            </a>
                            <a class="btn btn-info btn-sm" href="#">
                                <i class="fas fa-pencil-alt">
                                </i>
                                Edit
                            </a>
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
    </div>
    <!-- /.card -->
    <div class="float-right" style="margin-top: 10px; font-size: 0.75rem; padding: 5px;">
        {{$projects->links('vendor.pagination.default')}}
    </div>
</section>
<!-- /.content -->
@endsection
