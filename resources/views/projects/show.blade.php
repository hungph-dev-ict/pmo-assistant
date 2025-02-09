@extends('layouts.app')

@section('page_title')
    Project {{ $project->name }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('projects.index') }}">Projects</a></li>
    <li class="breadcrumb-item active">{{ $project->name }}</li>
@endsection

@section('content')
    <!-- Default box -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Projects Detail</h3>

            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                    <i class="fas fa-minus"></i>
                </button>
                <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-8 order-2 order-md-1">
                    <div class="row">
                        <div class="col-12 col-sm-4">
                            <div class="info-box bg-light">
                                <div class="info-box-content">
                                    <span class="info-box-text text-center text-muted">Estimated budget</span>
                                    <span
                                        class="info-box-number text-center text-muted mb-0">{{ $project->estimated_budget }}</span>
                                </div>
                            </div>
                            <div class="info-box bg-light">
                                <div class="info-box-content">
                                    <span class="info-box-text text-center text-muted">Status</span>
                                    <span class="info-box-number text-center text-muted mb-0">
                                        {{ $project->projectStatus->value1 ?? '' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-4">
                            <div class="info-box bg-light">
                                <div class="info-box-content">
                                    <span class="info-box-text text-center text-muted">Total amount spent</span>
                                    <span class="info-box-number text-center text-muted mb-0">1000</span>
                                </div>
                            </div>
                            <div class="info-box bg-light">
                                <div class="info-box-content">
                                    <span class="info-box-text text-center text-muted">Start date</span>
                                    <span
                                        class="info-box-number text-center text-muted mb-0">{{ $project->start_date }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-4">
                            <div class="info-box bg-light">
                                <div class="info-box-content">
                                    <span class="info-box-text text-center text-muted">Estimated project duration</span>
                                    <span
                                        class="info-box-number text-center text-muted mb-0">{{ $project->estimated_project_duration }}</span>
                                </div>
                            </div>
                            <div class="info-box bg-light">
                                <div class="info-box-content">
                                    <span class="info-box-text text-center text-muted">End date</span>
                                    <span
                                        class="info-box-number text-center text-muted mb-0">{{ $project->end_date }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <h4>Recent Activity</h4>
                            {{-- <div class="post">
                                <div class="user-block">
                                    <img class="img-circle img-bordered-sm" src="../../dist/img/user1-128x128.jpg"
                                        alt="user image">
                                    <span class="username">
                                        <a href="#">{{$project->name}}</a>
                                    </span>
                                    <span class="description">{{$project->status}} - {{ $project->start_date}}</span>
                                </div>
                                <!-- /.user-block -->
                                <p>
                                    Lorem ipsum represents a long-held tradition for designers,
                                    typographers and the like. Some people hate it and argue for
                                    its demise, but others ignore.
                                </p>

                                <p>
                                    <a href="#" class="link-black text-sm"><i class="fas fa-link mr-1"></i> Demo
                                        File 1 v2</a>
                                </p>
                            </div> --}}
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-12 col-lg-4 order-1 order-md-2">
                    <h3 class="text-primary"><i class="fas fa-paint-brush"></i> {{ $project->name }}</h3>
                    <p class="text-muted">{{ $project->description }}</p>
                    <br>
                    <div class="text-muted">
                        <p class="text-sm">Client Company
                            <b class="d-block">{{ $project->client_company }}</b>
                        </p>
                        <p class="text-sm">Project Manager
                            <b class="d-block">
                                @if ($project->project_manager)
                                    {{ $project->projectManager->name ?? '' }}
                                @endif
                            </b>
                        </p>
                    </div>

                    <h5 class="mt-5 text-muted">Project files</h5>
                    <ul class="list-unstyled">
                        <li>
                            <a href="" class="btn-link text-secondary"><i class="far fa-fw fa-file-word"></i>
                                Functional-requirements.docx</a>
                        </li>
                        <li>
                            <a href="" class="btn-link text-secondary"><i class="far fa-fw fa-file-pdf"></i>
                                UAT.pdf</a>
                        </li>
                        <li>
                            <a href="" class="btn-link text-secondary"><i class="far fa-fw fa-envelope"></i>
                                Email-from-flatbal.mln</a>
                        </li>
                        <li>
                            <a href="" class="btn-link text-secondary"><i class="far fa-fw fa-image "></i>
                                Logo.png</a>
                        </li>
                        <li>
                            <a href="" class="btn-link text-secondary"><i class="far fa-fw fa-file-word"></i>
                                Contract-10_12_2014.docx</a>
                        </li>
                    </ul>
                    <div class="text-center mt-5 mb-3">
                        <a href="#" class="btn btn-sm btn-primary">Add files</a>
                        <a href="#" class="btn btn-sm btn-warning">Report contact</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.card-body -->
    </div>
    <!-- /.card -->
@endsection
