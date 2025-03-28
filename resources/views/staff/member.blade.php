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
                <h3 class="card-title">Dual Listbox Example</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-5">
                        <h5>Available Users</h5>
                        <!-- Search Box -->
                        <div class="input-group mb-3">
                            <input type="text" id="available-users-search" class="form-control"
                                placeholder="Search users..." />
                            <div class="input-group-append">
                                <button id="clear-search" class="btn btn-outline-secondary" type="button">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <!-- List -->
                        <select id="available-users" class="form-control" size="10" multiple>
                            <option value="1">User 1</option>
                            <option value="2">User 2</option>
                            <option value="3">User 3</option>
                            <option value="4">User 4</option>
                            <option value="5">User 5</option>
                            <option value="6">User 6</option>
                            <option value="7">User 7</option>
                        </select>
                    </div>
                    <div class="col-md-2 text-center mt-5">
                        <button id="add-user" class="btn btn-primary btn-block mb-2">&gt;</button>
                        <button id="remove-user" class="btn btn-secondary btn-block mb-2">&lt;</button>
                        <button id="add-all-users" class="btn btn-success btn-block mb-2">&gt;&gt;</button>
                        <button id="remove-all-users" class="btn btn-danger btn-block mb-2">&lt;&lt;</button>
                    </div>
                    <div class="col-md-5">
                        <h5>Selected Users</h5>
                        <!-- List -->
                        <select id="selected-users" class="form-control" size="10" multiple>
                            <!-- Initially empty -->
                        </select>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button class="btn btn-primary">Submit</button>
            </div>
        </div>
    </section>
@endsection

@section('inline_js')
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
        });
    </script>
@endsection
