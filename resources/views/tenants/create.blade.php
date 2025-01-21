@extends('layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Project Add</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Tenant Add</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-6">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">General</h3>

                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('tenants.store') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="tenantName">Tenant Name <span style="color: red;">*</span></label>
                                <input type="text" id="tenantName" name="tenant_name" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="tenantDescription">Tenant Description <span style="color: red;">*</span></label>
                                <textarea id="tenantDescription" name="tenant_description" class="form-control" rows="3"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="tenantPlan">Plan <span style="color: red;">*</span></label>
                                <select id="tenantPlan" name="tenant_plan" class="form-control custom-select">
                                    <option selected disabled>Select one</option>
                                    @foreach ($plans as $plan)
                                        <option value="{{ $plan->id }}">
                                            {{ $plan->name . ' - ' . $plan->price . '$ per user' }}</option>
                                    @endforeach
                                </select>
                            </div>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <div class="col-md-6">
                <div class="card card-secondary">
                    <div class="card-header">
                        <h3 class="card-title">Head Account</h3>

                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="haEmail">Email <span style="color: red;">*</span></label>
                            <input type="email" id="haEmail" name="ha_email" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="haAccount">Account <span style="color: red;">*</span></label>
                            <input type="text" id="haAccount" name="ha_account" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="haFullName">Full Name <span style="color: red;">*</span></label>
                            <input type="text" id="haFullName" name="ha_full_name" class="form-control">
                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <a href="#" class="btn btn-secondary">Cancel</a>
                <input type="submit" value="Create new Tenant" class="btn btn-success float-right">
            </div>
        </div>
    </section>
    <!-- /.content -->
    </form>
@endsection
