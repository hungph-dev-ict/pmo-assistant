@extends('layouts.app')

@section('content')
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
                            <input type="text" id="tenantName" name="tenant_name"
                                class="form-control @error('tenant_name') is-invalid @enderror"
                                value="{{ old('tenant_name') }}">
                            @error('tenant_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('tenant_name') }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="tenantDescription">Tenant Description <span style="color: red;">*</span></label>
                            <textarea id="tenantDescription" name="tenant_description"
                                class="form-control @error('tenant_description') is-invalid @enderror" rows="3">{{ old('tenant_description') }}</textarea>
                            @error('tenant_description')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('tenant_description') }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="tenantPlan">Plan <span style="color: red;">*</span></label>
                            <select id="tenantPlan" name="tenant_plan"
                                class="form-control custom-select @error('tenant_plan') is-invalid @enderror">
                                <option selected disabled>Select one</option>
                                @foreach ($plans as $plan)
                                    <option value="{{ $plan->id }}"
                                        {{ old('tenant_plan') == $plan->id ? 'selected' : '' }}>
                                        {{ $plan->name . ' - ' . $plan->price . '$ per user' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('tenant_plan')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('tenant_plan') }}</strong>
                                </span>
                            @enderror
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
                        <input type="text" id="haEmail" name="ha_email"
                            class="form-control @error('ha_email') is-invalid @enderror" value="{{ old('ha_email') }}">
                        @error('ha_email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('ha_email') }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="haAccount">Account <span style="color: red;">*</span></label>
                        <input type="text" id="haAccount" name="ha_account"
                            class="form-control @error('ha_account') is-invalid @enderror" value="{{ old('ha_account') }}">
                        @error('ha_account')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('ha_account') }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="haFullName">Full Name <span style="color: red;">*</span></label>
                        <input type="text" id="haFullName" name="ha_full_name"
                            class="form-control @error('ha_full_name') is-invalid @enderror"
                            value="{{ old('ha_full_name') }}">
                        @error('ha_full_name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('ha_full_name') }}</strong>
                            </span>
                        @enderror
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
    </form>
@endsection
