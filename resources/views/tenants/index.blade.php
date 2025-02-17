@extends('layouts.app')

@section('inline_css')
    @vite(['resources/js/plugins/toastr/toastr.min.css'])
@endsection

@section('page_title')
    {{ __('labels.tenants') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">{{ __('labels.tenants') }}</li>
@endsection

@section('content')
    <!-- Default box -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ __('labels.tenants') }}</h3>

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
                            {{ __('labels.tenant_name') }}
                        </th>
                        <th style="width: 41%">
                            {{ __('labels.tenant_description') }}
                        </th>
                        <th style="width: 3%" class="text-center">
                        </th>
                        <th style="width: 10%">
                            {{ __('labels.tenant_head_account') }}
                        </th>
                        <th style="width: 9%" class="text-center">
                            {{ __('labels.tenant_current_plan') }}
                        </th>
                        <th style="width: 15%" class="text-center">

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
                            <td>
                                <div class="d-flex justify-content-center">

                                    <!-- Kiểm tra nếu tenant đã bị xóa mềm để hiển thị Restore hoặc Delete -->
                                    @if ($tenant->trashed())
                                        <!-- Nếu tenant đã xóa mềm -->
                                        <form method="POST" action="{{ route('tenants.restore', $tenant->id) }}">
                                            @csrf
                                            <button type="submit"
                                                class="btn btn-success btn-sm">{{ __('labels.restore') }}</button>
                                        </form>
                                    @else
                                        <!-- Nút Edit -->
                                        <a class="btn btn-info btn-sm mr-2"
                                            href="{{ route('tenants.edit', $tenant->id) }}">
                                            <i class="fas fa-pencil-alt"></i> {{ __('labels.edit') }}
                                        </a>
                                        <a class="btn btn-danger btn-sm" data-toggle="modal"
                                            data-target="#confirmDeleteModal" data-tenant-id="{{ $tenant->id }}"
                                            data-tenant-name="{{ $tenant->name }}">
                                            <i class="fas fa-trash"></i> {{ __('labels.delete') }}
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
                {!! __('labels.showing_entries', [
                    'start' => $tenants->firstItem(),
                    'end' => $tenants->lastItem(),
                    'total' => $tenants->total(),
                ]) !!}
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
                    <h5 class="modal-title">{{ __('labels.confirm_delete_tenant') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>{!! __('labels.confirm_delete_tenant_message') !!}</p>
                </div>
                <div class="modal-footer justify-content-between">
                    <form method="POST" id="deleteTenantForm" action="{{ route('tenants.destroy', $tenant->id) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-light">{{ __('labels.delete') }}</button>
                    </form>
                    <button type="button" class="btn btn-outline-light"
                        data-dismiss="modal">{{ __('labels.cancel') }}</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('inline_js')
    @vite(['resources/js/plugins/toastr/toastr.min.js'])
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

        @if (session('success'))
            $(function() {
                toastr.success("{{ session('success') }}");
            });
        @endif

        @if (session('error'))
            $(function() {
                toastr.success("{{ session('error') }}");
            });
        @endif
    </script>
@endsection
