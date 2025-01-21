@extends('layouts.app')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Users</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Users</li>
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
            <h3 class="card-title">Users</h3>

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
                        <th style="width: 2%">
                            #
                        </th>
                        <th style="width: 4%">
                        </th>
                        <th style="width: 12%">
                            Name
                        </th>
                        <th style="width: 12%">
                            Email
                        </th>
                        <th style="width: 14%" class="text-center">
                            Job Position
                        </th>
                        <th style="width: 8%" class="text-center">
                            Status
                        </th>
                        <th style="width: 14%">
                            Created At
                        </th>
                        <th style="width: 14%">
                            Updated At
                        </th>
                        <th style="width: 20%" class="text-center">
                            Action
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td>
                            {{ $user->id }}
                        </td>
                        <td>
                            <ul class="list-inline">
                                <li class="list-inline-item">
                                    <img alt="Avatar" class="table-avatar" src="{{ Vite::asset('resources/images/adminlte/avatar4.png') }}">
                                </li>
                            </ul>
                        </td>
                        <td>
                            <a>
                                {{ $user->account }}
                            </a>
                            <br />
                            <small>
                                {{ $user->name }}
                            </small>
                        </td>

                        <td>
                            {{ $user->email }}
                        </td>
                        <td class="project-state">
                            <a>
                                {{ $user->jobPosition->value1 ?? 'N/A' }}
                            </a>
                            <br />
                            <small>
                                {{ $user->jobPosition->value2 ?? 'N/A' }}
                            </small>
                        </td>
                        <td class="project-state">
                            @if ($user->status == 0)
                            <span class="badge badge-secondary">{{ $user->userStatus->value1 ?? 'N/A' }}</span> <!-- Màu xám khi status là 0 -->
                            @elseif ($user->status == 1)
                            <span class="badge badge-success">{{ $user->userStatus->value1 ?? 'N/A' }}</span> <!-- Màu xanh khi status là 1 -->
                            @else
                            <span class="badge badge-warning">{{ $user->userStatus->value1 ?? 'N/A' }}</span> <!-- Màu vàng khi N/A -->
                            @endif
                        </td>
                        <td>
                            {{ $user->created_at }}
                        </td>
                        <td>
                            {{ $user->updated_at }}
                        </td>
                        <td class="project-actions text-center">
                            <a class="btn btn-info btn-sm" href="#">
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
                    @endforeach
                </tbody>
            </table>
        </div>
        <!-- /.card-body -->
        <div class="card-footer d-flex justify-content-between align-items-center" style="padding: 0.5rem 1rem;">
            <div class="d-flex">
                Showing
                <strong class="mx-1">{{ $users->firstItem() }}</strong>
                to
                <strong class="mx-1">{{ $users->lastItem() }}</strong>
                of
                <strong class="mx-1">{{ $users->total() }}</strong>
                entries
            </div>
            <div class="pagination-wrapper ml-auto">
                {{ $users->links('vendor.pagination.default') }}
            </div>
        </div>
    </div>
    <!-- /.card -->

    <!-- Modal -->
    <div class="modal fade" id="modal-danger" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content bg-danger">
                <div class="modal-header">
                    <h5 class="modal-title">Danger Modal</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>One fine body&hellip;</p>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-outline-light" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-outline-light">Save changes</button>
                </div>
            </div>
        </div>
    </div>

</section>
<!-- /.content -->
@endsection