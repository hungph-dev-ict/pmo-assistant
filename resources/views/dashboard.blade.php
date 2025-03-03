@extends('layouts.app')

@section('page_title')
    Dashboard
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">Dashboard</li>
@endsection

@php
    use Illuminate\Support\Str;
@endphp

@section('content')
    <!-- Small boxes (Stat box) -->
    <div class="row">
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $project_count }}</h3>
                    <p>Number of Projects</p>
                </div>
                <div class="icon">
                    <i class="ion ion-bag"></i>
                </div>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $user_count }}</h3>
                    <p>Number of Employees</p>
                </div>
                <div class="icon">
                    <i class="ion ion-stats-bars"></i>
                </div>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $task_done_count }}</h3>
                    <p>Completed Tasks</p>
                </div>
                <div class="icon">
                    <i class="ion ion-person-add"></i>
                </div>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $contribution_rate }}<sup style="font-size: 20px">%</sup></h3>
                    <p>Your Contribution Rate</p>
                </div>
                <div class="icon">
                    <i class="ion ion-pie-graph"></i>
                </div>
            </div>
        </div>
        <!-- ./col -->
    </div>
    <!-- /.row -->
    <div class="row">
        <div class="col-6">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Your Incomplete Tasks</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                @if (!$incompleteTasks->isEmpty())
                    <div class="card-body table-responsive p-0">
                        <table class="table table-sm table-hover table-valign-middle">
                            <thead>
                                <tr>
                                    <th style="width: 12%">Project</th>
                                    <th style="width: 30%">Title</th>
                                    <th style="width: 8%" class="text-center">Priority</th>
                                    <th style="width: 10%">Status</th>
                                    <th style="width: 20%">Plan Start Date</th>
                                    <th style="width: 20%">Plan End Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($incompleteTasks as $task)
                                    <tr>
                                        <td>
                                            <a
                                                href="{{ auth()->user()->hasRole('pm') ? route('pm.task', [$task->project->id]) : route('staff.task', [$task->project->id]) }}">
                                                {{ Str::limit($task->project->name, 12, '...') }}
                                            </a>
                                        </td>
                                        <td>
                                            <a href="{{ route('task.redirect', [$task->project->id, $task->id]) }}">
                                                {{ Str::limit($task->name, 47, '...') }}
                                            </a>
                                        </td>
                                        <td class="text-center">
                                            <x-priority-icon :priority="$task->taskPriority->value1" />
                                        </td>
                                        <td>
                                            <span
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
                @else
                    <div class="card-body text-center text-success">
                        🎉 Congratulations! It looks like you've completed all your tasks.
                    </div>
                @endif
            </div>
        </div>
        <div class="col-6">
            <div class="card card-danger">
                <div class="card-header">
                    <h3 class="card-title">Your Critical Tasks</h3>
                    <div class="card-tools">
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                </div>
                @if (!$criticalTasks->isEmpty())
                    <div class="card-body table-responsive p-0">
                        <table class="table table-sm table-hover table-valign-middle">
                            <thead>
                                <tr>
                                    <th style="width: 12%">Project</th>
                                    <th style="width: 30%">Title</th>
                                    <th style="width: 5%" class="text-center">Priority</th>
                                    <th style="width: 5%">Status</th>
                                    <th style="width: 14%">Plan Start</th>
                                    <th style="width: 14%">Plan End</th>
                                    <th style="width: 10%">Plan(H)</th>
                                    <th style="width: 10%">Actual(H)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($criticalTasks as $task)
                                    <tr>
                                        <td>
                                            <a
                                                href="{{ auth()->user()->hasRole('pm') ? route('pm.task', [$task->project->id]) : route('staff.task', [$task->project->id]) }}">
                                                {{ Str::limit($task->project->name, 12, '...') }}
                                            </a>
                                        </td>
                                        <td>
                                            <a href="{{ route('task.redirect', [$task->project->id, $task->id]) }}">
                                                {{ Str::limit($task->name, 47, '...') }}
                                            </a>
                                        </td>
                                        <td class="text-center">
                                            <x-priority-icon :priority="$task->taskPriority->value1" />
                                        </td>
                                        <td>
                                            <span
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
                                        <td>{{ $task->plan_effort }}</td>
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
                @else
                    <div class="card-body text-center text-success">
                        🎉 Congratulations! All your tasks seem to be in good shape.
                    </div>
                @endif
            </div>
        </div>

    </div>
@endsection
