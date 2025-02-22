@extends('layouts.app')

@section('inline_css')
    @vite(['resources/js/plugins/toastr/toastr.min.css'])
@endsection

@section('page_title')
    User Edit
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('client.users.list', auth()->user()->tenant_id) }}">Users</a></li>
    <li class="breadcrumb-item active">User Edit</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-6">
            <!-- general form elements -->
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">User Edit Form</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <!-- /.card-header -->
                <form
                    action="{{ route('client.users.update', ['tenant_id' => auth()->user()->tenant_id, 'user_id' => $user->id]) }}"
                    method="POST">
                    @csrf
                    @method('PUT')
                    <!-- form start -->
                    <div class="card-body">
                        <div class="form-group">
                            <label for="userEmail">Email Address <span style="color: red;">*</span></label>
                            <input type="email" id="userEmail" name="user_email" placeholder="Enter Email"
                                class="form-control @error('user_email') is-invalid @enderror"
                                value="{{ old('user_email', $user->email) }}">
                            @error('user_email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('user_email') }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="userAccount">Account <span style="color: red;">*</span></label>
                            <input type="text" id="userAccount" name="user_account" placeholder="Enter Account"
                                class="form-control @error('user_account') is-invalid @enderror"
                                value="{{ old('user_account', $user->account) }}">
                            @error('user_account')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('user_account') }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="userName">Full Name <span style="color: red;">*</span></label>
                            <input type="text" id="userName" name="user_name" placeholder="Enter Name"
                                class="form-control @error('user_name') is-invalid @enderror"
                                value="{{ old('user_name', $user->name) }}">
                            @error('user_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('user_name') }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="userJobPosition">Main role<span style="color: red;">*</span></label>
                            <select id="userJobPosition" name="user_job_position"
                                class="form-control select @error('user_job_position') is-invalid @enderror">
                                <option selected disabled>-- Select Main Role --</option>
                                @foreach ($jobPositions as $jobPosition)
                                    <option value="{{ $jobPosition->key }}"
                                        {{ old('user_job_position', $user->job_position) == $jobPosition->key ? 'selected' : '' }}>
                                        {{ $jobPosition->value1 . ' - ' . $jobPosition->value2 }}
                                    </option>
                                @endforeach
                            </select>
                            @error('user_job_position')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('user_job_position') }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="userSubRole1">Sub Role 1</label>
                            <select id="userSubRole1" name="user_sub_role_1"
                                class="form-control select @error('user_sub_role_1') is-invalid @enderror">
                                <option selected disabled>-- Select Sub Role 1 --</option>
                                @foreach ($jobPositions as $jobPosition)
                                    <option value="{{ $jobPosition->key }}"
                                        {{ old('user_sub_role_1', $user->sub_role_1) == $jobPosition->key ? 'selected' : '' }}>
                                        {{ $jobPosition->value1 . ' - ' . $jobPosition->value2 }}
                                    </option>
                                @endforeach
                            </select>
                            @error('user_sub_role_1')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('user_sub_role_1') }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="userSubRole2">Sub Role 2</label>
                            <select id="userSubRole2" name="user_sub_role_2"
                                class="form-control select @error('user_sub_role_2') is-invalid @enderror">
                                <option selected disabled>-- Select Sub Role 2 --</option>
                                @foreach ($jobPositions as $jobPosition)
                                    <option value="{{ $jobPosition->key }}"
                                        {{ old('user_sub_role_2', $user->sub_role_2) == $jobPosition->key ? 'selected' : '' }}>
                                        {{ $jobPosition->value1 . ' - ' . $jobPosition->value2 }}
                                    </option>
                                @endforeach
                            </select>
                            @error('user_sub_role_2')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('user_sub_role_2') }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <input type="submit" value="Submit" class="btn btn-primary">
                    </div>
                </form>
            </div>
            <!-- /.card -->
        </div>
    </div>
    <!-- /.content -->
@endsection

@section('inline_js')
    @vite(['resources/js/plugins/toastr/toastr.min.js'])
@endsection
