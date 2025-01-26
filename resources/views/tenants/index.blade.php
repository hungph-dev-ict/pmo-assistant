@extends('layouts.app')

@section('inline_css')
    <link rel="stylesheet" href="{{ asset('build/css/plugins/toastr-css.css') }}">
@endsection

@section('page_title')
    Tenants
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Tenants</li>
@endsection

@section('content')
    <!-- Default box -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Tenants</h3>

            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                    <i class="fas fa-minus"></i>
                </button>
                {{-- <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
                        <i class="fas fa-times"></i>
                    </button> --}}
            </div>
        </div>
        <div class="card-body p-0">
            <table class="table projects">
                <thead>
                    <tr>
                        <th style="width: 2%" class="text-center">
                            #
                        </th>
                        <th style="width: 5%" class="text-center">
                        </th>
                        <th style="width: 15%">
                            Name
                        </th>
                        <th style="width: 41%">
                            Description
                        </th>
                        <th style="width: 3%" class="text-center">
                        </th>
                        <th style="width: 10%">
                            Head Account
                        </th>
                        <th style="width: 9%" class="text-center">
                            Current Plan
                        </th>
                        <th style="width: 15%" class="text-center">
                            Action
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tenants as $tenant)
                        <tr class="{{ $tenant->trashed() ? 'table-secondary' : '' }}">
                            <td>
                                {{ $tenant->id }}
                            </td>
                            <td>
                                <img src="{{ $tenant->logo_base64 }}" alt="Logo of {{ $tenant->name }}"
                                    style="max-width: 100%; max-height: 3.8rem;" loading="lazy" />
                            </td>
                            <td>
                                {{ $tenant->name }}
                            </td>
                            <td>
                                {{ $tenant->description }}
                            </td>
                            <td>
                                <img src="{{ $tenant->ha_avatar_base64 }}" alt="Avatar of {{ $tenant->headUser->name }}"
                                    style="max-width: 100%; max-height: 3.8rem;" loading="lazy" />
                            </td>
                            <td>
                                <a>
                                    {{ $tenant->headUser->account ?? 'N/A' }}
                                </a>
                                <br />
                                <small>
                                    {{ $tenant->headUser->name ?? 'N/A' }}
                                </small>
                            </td>

                            <td class="text-center">
                                {{ $tenant->plan->name ?? 'N/A' }}
                            </td>
                            <td class="project-actions">
                                <div class="d-flex justify-content-center">

                                    <!-- Kiểm tra nếu tenant đã bị xóa mềm để hiển thị Restore hoặc Delete -->
                                    @if ($tenant->trashed())
                                        <!-- Nếu tenant đã xóa mềm -->
                                        <form method="POST" action="{{ route('tenants.restore', $tenant->id) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm">Restore</button>
                                        </form>
                                    @else
                                        <!-- Nút Edit -->
                                        <a class="btn btn-info btn-sm mr-2" href="{{ route('tenants.edit', $tenant->id) }}">
                                            <i class="fas fa-pencil-alt"></i> Edit
                                        </a>
                                        <a class="btn btn-danger btn-sm" data-toggle="modal"
                                            data-target="#confirmDeleteModal" data-tenant-id="{{ $tenant->id }}"
                                            data-tenant-name="{{ $tenant->name }}">
                                            <i class="fas fa-trash"></i> Delete
                                        </a>
                                    @endif
                                </div>
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
                <strong class="mx-1">{{ $tenants->firstItem() }}</strong>
                to
                <strong class="mx-1">{{ $tenants->lastItem() }}</strong>
                of
                <strong class="mx-1">{{ $tenants->total() }}</strong>
                entries
            </div>
            <div class="pagination-wrapper ml-auto">
                {{ $tenants->links('vendor.pagination.bootstrap-4') }}
            </div>
        </div>
    </div>
    <!-- /.card -->

    <!-- Modal -->
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content bg-danger">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Delete Tenant</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete tenant <strong id="tenantName"></strong>? This action cannot be
                        undone.</p>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-outline-light" data-dismiss="modal">Cancel</button>
                    <form method="POST" id="deleteTenantForm" action="{{ route('tenants.destroy', $tenant->id) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-light">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('inline_js')
    <script src="{{ asset('build/js/plugins/toastr-js.js') }}"></script>
@endsection

@section('custom_inline_js')
    <script>
        $('#confirmDeleteModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget); // Nút được click để mở modal
            var tenantId = button.data('tenant-id'); // Lấy ID của tenant
            var tenantName = button.data('tenant-name'); // Lấy tên của tenant

            var modal = $(this);
            modal.find('#tenantName').text(tenantName); // Hiển thị tên tenant
            var deleteUrl = '/tenants/' + tenantId;
            modal.find('#deleteTenantForm').attr('action', deleteUrl); // Cập nhật URL trong action
        });
    </script>

    @if (session('success'))
        <script>
            toastr.success("{{ session('success') }}");
        </script>
    @endif

    @if (session('error'))
        <script>
            toastr.error("{{ session('error') }}");
        </script>
    @endif
@endsection
