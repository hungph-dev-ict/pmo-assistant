@extends('layouts.app')

@section('inline_css')
    @vite(['resources/js/plugins/toastr/toastr.min.css'])
@endsection

@section('page_title')
    {{ $project->name }} - {{__('labels.member')}}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">{{ $project->name }} - {{__('labels.member')}}</li>
@endsection

@section('content')
    <!-- Default box -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{__('labels.project_member')}}</h3>
        </div>
        <form id="projectMembersForm" action="{{ route('pm.member.update', ['project_id' => $project->id]) }}" method="POST">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-md-5">
                        <h5>{{__('labels.available_users')}}</h5>
                        <!-- Search Box -->
                        <div class="input-group mb-3">
                            <input type="text" id="available-users-search" class="form-control"
                                placeholder="{{ __('labels.search_users') }}" />
                            <div class="input-group-append">
                                <button id="clear-search" class="btn btn-outline-secondary" type="button">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <!-- List -->
                        <select id="available-users" class="form-control" size="10" multiple>
                            @foreach ($membersNotInProject as $memberNotInProject)
                                <option value="{{ $memberNotInProject->id }}">
                                    {{ $memberNotInProject->account . ' - ' . $memberNotInProject->name . ' - ' . $memberNotInProject->jobPosition->value1 }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 text-center mt-5">
                        <button type="button" id="add-user" class="btn btn-primary btn-block mb-2">&gt;</button>
                        <button type="button" id="remove-user" class="btn btn-secondary btn-block mb-2">&lt;</button>
                        <button type="button" id="add-all-users" class="btn btn-success btn-block mb-2">&gt;&gt;</button>
                        <button type="button" id="remove-all-users" class="btn btn-danger btn-block mb-2">&lt;&lt;</button>
                    </div>
                    <div class="col-md-5">
                        <h5>{{__('labels.selected_users')}}</h5>
                        <!-- List -->
                        <select id="selected-users" class="form-control" size="10" multiple>
                            @foreach ($membersInProject as $memberInProject)
                                <option value="{{ $memberInProject->id }}">
                                    {{ $memberInProject->account . ' - ' . $memberInProject->name . ' - ' . $memberInProject->jobPosition->value1 }}
                                </option>
                            @endforeach
                        </select>
                        <input type="hidden" name="selected_user_list" id="selectedUserList">
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">{{ __('labels.submit') }}</button>
            </div>
        </form>
    </div>
@endsection

@section('inline_js')
    @vite(['resources/js/plugins/toastr/toastr.min.js'])
@endsection

@section('custom_inline_js')
    <!-- Script for Dual Listbox functionality -->
    <script>
        $(function() {
            // Move selected users to the "Selected" list
            $('#add-user').click(function() {
                $('#available-users option:selected').appendTo('#selected-users');
            });

            // Move selected users back to the "Available" list
            $('#remove-user').click(function() {
                $('#selected-users option:selected').appendTo('#available-users');
            });

            // Move all users to the "Selected" list
            $('#add-all-users').click(function() {
                $('#available-users option').appendTo('#selected-users');
            });

            // Move all users back to the "Available" list
            $('#remove-all-users').click(function() {
                $('#selected-users option').appendTo('#available-users');
            });

            // Search and filter the available users (using jQuery)
            $('#available-users-search').on('input', function() {
                var searchValue = $(this).val().toLowerCase();
                $('#available-users option').each(function() {
                    var optionText = $(this).text().toLowerCase();
                    if (optionText.indexOf(searchValue) !== -1) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });

            // Clear the search input and show all users (using jQuery)
            $('#clear-search').click(function() {
                var searchBox = $('#available-users-search');
                var options = $('#available-users option');

                // Clear search box value
                searchBox.val('');

                // Reset all options to visible
                options.show();

                // Focus back to the search box
                searchBox.focus();
            });
            // Thêm tất cả option từ elected-users và input
            document.getElementById("projectMembersForm").addEventListener("submit", function() {
                const selectedUsers = document.getElementById("selected-users");
                const allOptionValues = Array.from(selectedUsers.options).map(option => option.value);
                document.getElementById("selectedUserList").value = JSON.stringify(allOptionValues);
            });

            @if (session('success'))
                toastr.success("{{ session('success') }}");
            @endif

            @if (session('warning'))
                toastr.warning("{{ session('warning') }}");
            @endif

            @if (session('error'))
                toastr.error("{{ session('error') }}");
            @endif
        });
    </script>
@endsection
