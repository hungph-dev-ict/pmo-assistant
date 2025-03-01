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
                <div class="card-header">
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
                    <table class="table table-sm table-hover table-valign-middle">
                        <thead>
                            <tr>
                                <th style="width: 40%">Title</th>
                                <th style="width: 10%" class="text-center">Priority</th>
                                <th style="width: 10%">Status</th>
                                <th style="width: 20%">Plan Start Date</th>
                                <th style="width: 20%">Actual Start Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($incompleteTasks as $task)
                                <tr>
                                    <td>
                                        {{ $task->name }}
                                    </td>
                                    <td class="text-center"><x-priority-icon :priority="$task->taskPriority->value1" /></td>
                                    <td><span
                                            class="{{ getStatusClass($task->status) }}">{{ $task->taskStatus->value1 }}</span>
                                    </td>
                                    <td class="text-nowrap">
                                        {{ $task->plan_start_date }}
                                        @if ($task->delayed)
                                            🔥
                                        @endif
                                    </td>
                                    <td class="text-nowrap">
                                        {{ $task->plan_end_date }}
                                        @if ($task->overdue)
                                            🔥
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer clearfix">
                    <div class="pagination pagination-sm float-right" style="height: 30px">
                        {{ $incompleteTasks->appends(['it_page' => request('it_page')])->links('vendor.pagination.bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Your Critical Tasks</h3>
                    <div class="card-tools">
                        {{-- <ul class="pagination pagination-sm float-right">
                            <li class="page-item"><a class="page-link" href="#">&laquo;</a></li>
                            <li class="page-item"><a class="page-link" href="#">1</a></li>
                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                            <li class="page-item"><a class="page-link" href="#">&raquo;</a></li>
                        </ul> --}}

                    </div>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-sm table-hover table-valign-middle">
                        <thead>
                            <tr>
                                <th style="width: 40%">Title</th>
                                <th style="width: 5%" class="text-center">Priority</th>
                                <th style="width: 5%">Status</th>
                                <th style="width: 15%">Plan Start</th>
                                <th style="width: 15%">Actual Start</th>
                                <th style="width: 10%">Plan(H)</th>
                                <th style="width: 10%">Actual(H)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($criticalTasks as $task)
                                <tr>
                                    <td>
                                        {{ $task->name }}
                                    </td>
                                    <td class="text-center"><x-priority-icon :priority="$task->taskPriority->value1" /></td>
                                    <td><span
                                            class="{{ getStatusClass($task->status) }}">{{ $task->taskStatus->value1 }}</span>
                                    </td>
                                    <td class="text-nowrap">
                                        {{ $task->plan_start_date }}
                                        @if ($task->delayed)
                                            🔥
                                        @endif
                                    </td>
                                    <td class="text-nowrap">
                                        {{ $task->plan_end_date }}
                                        @if ($task->overdue)
                                            🔥
                                        @endif
                                    </td>
                                    <td>
                                        {{ $task->estimate_effort }}
                                    </td>
                                    <td>
                                        {{ $task->actual_effort }}
                                        @if ($task->overcost)
                                            <i class="fas fa-exclamation-triangle text-danger ml-2"
                                                title="Actual effort exceeds plan effort"></i>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer clearfix">
                    <div class="pagination pagination-sm float-right" style="height: 30px">
                        {{ $criticalTasks->appends(['ct_page' => request('ct_page')])->links('vendor.pagination.bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
