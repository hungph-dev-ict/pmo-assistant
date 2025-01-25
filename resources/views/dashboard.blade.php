@extends('layouts.app')

@section('page_title')
    Dashboard
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">Dashboard</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('messages.dashboard') }}</h3>
                </div>
                <div class="card-body">
                    <p>{{ __('messages.welcome') }}</p>
                    <!-- You can add more components here like charts, tables, etc. -->
                </div>
            </div>
        </div>
    </div>
@endsection
