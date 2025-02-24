@extends('layouts.app')

@section('inline_css')
    @vite(['resources/js/plugins/toastr/toastr.min.css'])
@endsection

@section('page_title')
    Users
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">User</li>
@endsection

<!-- Main content -->
@section('content')
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
                        <th style="width: 11%">
                            Email
                        </th>
                        <th style="width: 11%" class="text-center">
                            Job Position
                        </th>
                        <th style="width: 11%" class="text-center">
                            Sub Role 1
                        </th>
                        <th style="width: 11%" class="text-center">
                            Sub Role 2
                        </th>
                        <th style="width: 8%" class="text-center">
                            Status
                        </th>
                        <th style="width: 10%">
                            Created At
                        </th>
                        <th style="width: 20%" class="text-center">
                            Action
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>
                                {{ $user->id }}
                            </td>
                            <td>
                                <ul class="list-inline">
                                    <li class="list-inline-item">
                                        <img alt="Avatar" class="table-avatar"
                                            src="{{ Vite::asset('resources/images/adminlte/avatar4.png') }}">
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
                                    {{ $user->jobPosition->value1 ?? '-' }}
                                </a>
                                <br />
                                <small>
                                    {{ $user->jobPosition->value2 ?? '' }}
                                </small>
                            </td>
                            <td class="project-state">
                                <a>
                                    {{ $user->subRole1->value1 ?? '-' }}
                                </a>
                                <br />
                                <small>
                                    {{ $user->subRole1->value2 ?? '' }}
                                </small>
                            </td>
                            <td class="project-state">
                                <a>
                                    {{ $user->subRole2->value1 ?? '-' }}
                                </a>
                                <br />
                                <small>
                                    {{ $user->subRole2->value2 ?? '' }}
                                </small>
                            </td>
                            <td class="project-state">
                                @if ($user->status == 0)
                                    <span class="badge badge-secondary">{{ $user->userStatus->value1 ?? '-' }}</span>
                                    <!-- Màu xám khi status là 0 -->
                                @elseif ($user->status == 1)
                                    <span class="badge badge-success">{{ $user->userStatus->value1 ?? '-' }}</span>
                                    <!-- Màu xanh khi status là 1 -->
                                @else
                                    <span class="badge badge-warning">{{ $user->userStatus->value1 ?? '-' }}</span>
                                    <!-- Màu vàng khi - -->
                                @endif
                            </td>
                            <td>
                                {{ $user->created_at }}
                            </td>
                            <td class="text-center">
                                @if ($user->trashed())
                                    <!-- Nếu tenant đã xóa mềm -->
                                    <form method="POST"
                                        action="{{ route('client.users.restore', ['tenant_id' => $tenant_id, 'user_id' => $user->id]) }}">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm">Restore</button>
                                    </form>
                                @else
                                    <a class="btn btn-info btn-sm" href="{{ route('client.users.edit', ['tenant_id' => $tenant_id, 'user_id' => $user->id]) }}">
                                        <i class="fas fa-pencil-alt">
                                        </i>
                                        Edit
                                    </a>
                                    <a class="btn btn-danger btn-sm" href="#" data-toggle="modal"
                                        data-target="#confirmDeleteModal" data-user-id="{{ $user->id }}"
                                        data-tenant-id="{{ $tenant_id }}" data-user-name="{{ $user->name }}">
                                        <i class="fas fa-trash">
                                        </i>
                                        Delete
                                    </a>
                                @endif
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
                {{ $users->links('vendor.pagination.bootstrap-4') }}
            </div>
        </div>
    </div>
    <!-- /.card -->

    <!-- Modal -->
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content bg-danger">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Delete User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete user <strong id="userName"></strong>? This action cannot be
                        undone.</p>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-outline-light" data-dismiss="modal">Cancel</button>
                    <form method="POST" id="deleteUserForm"
                        action="{{ route('client.users.destroy', ['tenant_id' => $tenant_id, 'user_id' => $user->id]) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-light">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- /.content -->
@endsection

@section('inline_js')
    @vite(['resources/js/plugins/toastr/toastr.min.js'])
@endsection

@section('custom_inline_js')
    <script>
        $('#confirmDeleteModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget); // Nút được click để mở modal
            var userId = button.data('user-id'); // Lấy ID của user
            var tenantId = button.data('tenant-id');
            var userName = button.data('user-name'); // Lấy tên của user

            var modal = $(this);
            modal.find('#userName').text(userName); // Hiển thị tên user
            var deleteUrl = '/tenant/' + tenantId + '/users/' + userId;
            modal.find('#deleteUserForm').attr('action', deleteUrl); // Cập nhật URL trong action
        });
        @if (session('success'))
            $(function() {
                toastr.success("{{ session('success') }}");
            });
        @endif
    </script>
@endsection
