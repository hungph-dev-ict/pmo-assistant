@extends('layouts.app')

@section('inline_css')
    <link rel="stylesheet" href="{{ asset('build/css/plugins/toastr-css.css') }}">
@endsection

@section('page_title')
    Projects
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Projects</li>
@endsection

@section('content')
    <!-- Default box -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Projects</h3>

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
                        <th style="width: 2%">
                            #
                        </th>
                        <th style="width: 20%">
                            Project Name
                        </th>
                        <th style="width: 30%">
                            Description
                        </th>
                        <th style="width: 14%" class="text-center">
                            Start Date
                        </th>
                        <th style="width: 14%" class="text-center">
                            End Date
                        </th>
                        <th style="width: 20%" class="text-center">
                            Action
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($projects as $project)
                        <tr class="{{ $project->trashed() ? 'table-secondary' : '' }}">
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
                            <td class="project-actions">
                                <div class="d-flex justify-content-center">
                                    <!-- <a class="btn btn-info btn-sm" href="#">
                                                <i class="fas fa-pencil-alt">
                                                </i>
                                                Edit
                                            </a> -->
                                    <!-- Kiểm tra nếu project đã bị xóa mềm để hiển thị Restore hoặc Delete -->
                                    @if ($project->trashed())
                                        <!-- Nếu project đã xóa mềm -->
                                        <form method="POST" action="{{ route('projects.restore', $project->id) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm">Restore</button>
                                        </form>
                                    @else
                                        <a class="btn btn-primary btn-sm mr-2"
                                            href="{{ route('projects.show', $project->id) }}">
                                            <i class="fas fa-folder">
                                            </i>
                                            View
                                        </a>
                                        <a class="btn btn-danger btn-sm" data-toggle="modal"
                                            data-target="#confirmDeleteModal" data-project-id="{{ $project->id }}"
                                            data-project-name="{{ $project->name }}">
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
                <strong class="mx-1">{{ $projects->firstItem() }}</strong>
                to
                <strong class="mx-1">{{ $projects->lastItem() }}</strong>
                of
                <strong class="mx-1">{{ $projects->total() }}</strong>
                entries
            </div>
            <div class="pagination-wrapper ml-auto">
                {{ $projects->links('vendor.pagination.bootstrap-4') }}
            </div>
        </div>
    </div>
    <!-- /.card -->
    <!-- Modal -->
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content bg-danger">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Delete Project</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete project <strong id="projectName"></strong>? This action cannot be
                        undone.</p>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-outline-light" data-dismiss="modal">Cancel</button>
                    <form method="POST" id="deleteProjectForm" action="{{ route('projects.destroy', $project->id) }}">
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
            var projectId = button.data('project-id'); // Lấy ID của project
            var projectName = button.data('project-name'); // Lấy tên của project

            var modal = $(this);
            modal.find('#projectName').text(projectName); // Hiển thị tên project
            var deleteUrl = '/projects/' + projectId;
            modal.find('#deleteProjectForm').attr('action', deleteUrl); // Cập nhật URL trong action
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
