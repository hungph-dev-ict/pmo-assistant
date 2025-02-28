@extends('layouts.app')

@section('page_title')
    Dashboard
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">Dashboard</li>
@endsection

@section('content')
    <!-- Small boxes (Stat box) -->
    <div class="row">
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $project_count }}</h3>

                    <p>Project Numbers Orders</p>
                </div>
                <div class="icon">
                    <i class="ion ion-bag"></i>
                </div>
                {{-- <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a> --}}
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>53<sup style="font-size: 20px">%</sup></h3>

                    <p>Bounce Rate</p>
                </div>
                <div class="icon">
                    <i class="ion ion-stats-bars"></i>
                </div>
                <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $user_count }}</h3>

                    <p>Employee Number</p>
                </div>
                <div class="icon">
                    <i class="ion ion-person-add"></i>
                </div>
                <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>65</h3>

                    <p>Unique Visitors</p>
                </div>
                <div class="icon">
                    <i class="ion ion-pie-graph"></i>
                </div>
                <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
    </div>
    <!-- /.row -->
    <div class="row">
        <div class="col-6">
            <div class="card">
                <div class="card-header border-0">
                    <h3 class="card-title">Your Incomplete Tasks</h3>
                    {{-- <div class="card-tools">
                        <a href="#" class="btn btn-tool btn-sm">
                            <i class="fas fa-download"></i>
                        </a>
                        <a href="#" class="btn btn-tool btn-sm">
                            <i class="fas fa-bars"></i>
                        </a>
                    </div> --}}
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-striped table-valign-middle">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Priority</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($incompleteTasks as $task)
                                <tr>
                                    <td>
                                        {{ $task->name }}
                                    </td>
                                    <td>{{ $task->priority }}</td>
                                    <td>
                                        {{ $task->status }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="card">
                <div class="card-header border-0">
                    <h3 class="card-title">Your Critical Tasks</h3>
                    {{-- <div class="card-tools">
                        <a href="#" class="btn btn-tool btn-sm">
                            <i class="fas fa-download"></i>
                        </a>
                        <a href="#" class="btn btn-tool btn-sm">
                            <i class="fas fa-bars"></i>
                        </a>
                    </div> --}}
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-striped table-valign-middle">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Priority</th>
                                <th>Status</th>
                                <th>Plan Start Date</th>
                                <th>Actual Start Date</th>
                                <th>Plan Effort</th>
                                <th>Actual Effort</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($criticalTasks as $task)
                                <tr>
                                    <td>
                                        {{ $task->name }}
                                    </td>
                                    <td>{{ $task->priority }}</td>
                                    <td>{{ $task->status }}</td>
                                    <td>
                                        {{ $task->plan_start_date }}
                                        @if ($task->alert_type == 'DelayedStart')
                                            🔥
                                        @endif
                                    </td>
                                    <td>
                                        {{ $task->plan_end_date }}
                                        @if ($task->alert_type == 'Overdue')
                                            🔥
                                        @endif
                                    </td>
                                    <td>
                                        {{ $task->estimate_effort }}
                                    </td>
                                    <td>
                                        @if ($task->alert_type == 'Overcost')
                                            <i class="fas fa-exclamation-triangle text-danger ml-2"
                                                title="Actual effort exceeds plan effort"></i>
                                        @endif
                                        {{ $task->actual_effort }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
@endsection
