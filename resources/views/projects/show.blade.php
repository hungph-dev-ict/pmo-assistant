@extends('layouts.app')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Project Detail</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Project Detail</li>
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
                                    <span class="info-box-number text-center text-muted mb-0">2300</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-4">
                            <div class="info-box bg-light">
                                <div class="info-box-content">
                                    <span class="info-box-text text-center text-muted">Total amount spent</span>
                                    <span class="info-box-number text-center text-muted mb-0">2000</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-4">
                            <div class="info-box bg-light">
                                <div class="info-box-content">
                                    <span class="info-box-text text-center text-muted">Estimated project duration</span>
                                    <span class="info-box-number text-center text-muted mb-0">20</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <h4>Recent Activity</h4>
                            <div class="post">
                                <div class="user-block">
                                    <img class="img-circle img-bordered-sm" src="../../dist/img/user1-128x128.jpg" alt="user image">
                                    <span class="username">
                                        <a href="#">Jonathan Burke Jr.</a>
                                    </span>
                                    <span class="description">Shared publicly - 7:45 PM today</span>
                                </div>
                                <!-- /.user-block -->
                                <p>
                                    Lorem ipsum represents a long-held tradition for designers,
                                    typographers and the like. Some people hate it and argue for
                                    its demise, but others ignore.
                                </p>

                                <p>
                                    <a href="#" class="link-black text-sm"><i class="fas fa-link mr-1"></i> Demo File 1 v2</a>
                                </p>
                            </div>

                            <div class="post clearfix">
                                <div class="user-block">
                                    <img class="img-circle img-bordered-sm" src="../../dist/img/user7-128x128.jpg" alt="User Image">
                                    <span class="username">
                                        <a href="#">Sarah Ross</a>
                                    </span>
                                    <span class="description">Sent you a message - 3 days ago</span>
                                </div>
                                <!-- /.user-block -->
                                <p>
                                    Lorem ipsum represents a long-held tradition for designers,
                                    typographers and the like. Some people hate it and argue for
                                    its demise, but others ignore.
                                </p>
                                <p>
                                    <a href="#" class="link-black text-sm"><i class="fas fa-link mr-1"></i> Demo File 2</a>
                                </p>
                            </div>

                            <div class="post">
                                <div class="user-block">
                                    <img class="img-circle img-bordered-sm" src="../../dist/img/user1-128x128.jpg" alt="user image">
                                    <span class="username">
                                        <a href="#">Jonathan Burke Jr.</a>
                                    </span>
                                    <span class="description">Shared publicly - 5 days ago</span>
                                </div>
                                <!-- /.user-block -->
                                <p>
                                    Lorem ipsum represents a long-held tradition for designers,
                                    typographers and the like. Some people hate it and argue for
                                    its demise, but others ignore.
                                </p>

                                <p>
                                    <a href="#" class="link-black text-sm"><i class="fas fa-link mr-1"></i> Demo File 1 v1</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-12 col-lg-4 order-1 order-md-2">
                    <h3 class="text-primary"><i class="fas fa-paint-brush"></i> AdminLTE v3</h3>
                    <p class="text-muted">Raw denim you probably haven't heard of them jean shorts Austin. Nesciunt tofu stumptown aliqua butcher retro keffiyeh dreamcatcher synth. Cosby sweater eu banh mi, qui irure terr.</p>
                    <br>
                    <div class="text-muted">
                        <p class="text-sm">Client Company
                            <b class="d-block">Deveint Inc</b>
                        </p>
                        <p class="text-sm">Project Leader
                            <b class="d-block">Tony Chicken</b>
                        </p>
                    </div>

                    <h5 class="mt-5 text-muted">Project files</h5>
                    <ul class="list-unstyled">
                        <li>
                            <a href="" class="btn-link text-secondary"><i class="far fa-fw fa-file-word"></i> Functional-requirements.docx</a>
                        </li>
                        <li>
                            <a href="" class="btn-link text-secondary"><i class="far fa-fw fa-file-pdf"></i> UAT.pdf</a>
                        </li>
                        <li>
                            <a href="" class="btn-link text-secondary"><i class="far fa-fw fa-envelope"></i> Email-from-flatbal.mln</a>
                        </li>
                        <li>
                            <a href="" class="btn-link text-secondary"><i class="far fa-fw fa-image "></i> Logo.png</a>
                        </li>
                        <li>
                            <a href="" class="btn-link text-secondary"><i class="far fa-fw fa-file-word"></i> Contract-10_12_2014.docx</a>
                        </li>
                    </ul>
                    <div class="text-center mt-5 mb-3">
                        <a href="#" class="btn btn-sm btn-primary">Add files</a>
                        <a href="#" class="btn btn-sm btn-warning">Report contact</a>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Task List</h3>
                    <a class="btn btn-success btn-sm float-right" data-toggle="modal" data-target="#modal-default">
                        <i class="fas fa-plus">
                        </i>
                        Add new Task
                    </a>
                </div>
                <div class="modal fade" id="modal-default">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Default Modal</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="exampleInputBorder">Bottom Border only <code>.form-control-border</code></label>
                                    <input type="text" class="form-control form-control-border" id="exampleInputBorder" placeholder=".form-control-border">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputBorderWidth2">Bottom Border only 2px Border <code>.form-control-border.border-width-2</code></label>
                                    <input type="text" class="form-control form-control-border border-width-2" id="exampleInputBorderWidth2" placeholder=".form-control-border.border-width-2">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputRounded0">Flat <code>.rounded-0</code></label>
                                    <input type="text" class="form-control rounded-0" id="exampleInputRounded0" placeholder=".rounded-0">
                                </div>
                                <h4>Custom Select</h4>
                                <div class="form-group">
                                    <label for="exampleSelectBorder">Bottom Border only <code>.form-control-border</code></label>
                                    <select class="custom-select form-control-border" id="exampleSelectBorder">
                                        <option>Value 1</option>
                                        <option>Value 2</option>
                                        <option>Value 3</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="exampleSelectBorderWidth2">Bottom Border only <code>.form-control-border.border-width-2</code></label>
                                    <select class="custom-select form-control-border border-width-2" id="exampleSelectBorderWidth2">
                                        <option>Value 1</option>
                                        <option>Value 2</option>
                                        <option>Value 3</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="exampleSelectRounded0">Flat <code>.rounded-0</code></label>
                                    <select class="custom-select rounded-0" id="exampleSelectRounded0">
                                        <option>Value 1</option>
                                        <option>Value 2</option>
                                        <option>Value 3</option>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer justify-content-between">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary">Save changes</button>
                            </div>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                </div>
                <!-- /.modal -->
                <!-- /.card-header -->
                <div class="card-body p-0">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th style="width:3%">#</th>
                                <th style="width: 16%">Task</th>
                                <th style="width: 26%">Sub-Task</th>
                                <th style="width: 8%">PIC</th>
                                <th style="width: 6.5%">Plan Start Date</th>
                                <th style="width: 6.5%">Plan End Date</th>
                                <th style="width: 6.5%">Actual Start Date</th>
                                <th style="width: 6.5%">Actual End Date</th>
                                <th style="width: 2%">Estimate Effort (MD)</th>
                                <th style="width: 2%">Actual Effort (MD)</th>
                                <th style="width: 2%">Progress</th>
                                <th style="width:3%"></th>
                                <th style="width: 12%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>4.</td>
                                <td>
                                    Parent
                                </td>
                                <td>
                                    Child
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="{{ Vite::asset('resources/images/adminlte/user2-160x160.jpg') }}" alt="Avatar" class="img-circle elevation-2" style="width: 25px; height: 25px; object-fit: cover; margin-right: 10px;">
                                        <span>HungPH5</span>
                                    </div>
                                </td>
                                <td>
                                    2025/01/01
                                </td>
                                <td>
                                    2025/01/01
                                </td>
                                <td>
                                    2025/01/01
                                </td>
                                <td>
                                    2025/01/01
                                </td>
                                <td>
                                    12
                                </td>
                                <td>
                                    30
                                </td>
                                <td>
                                    <div class="progress progress-xs progress-striped active mt-2">
                                        <div class="progress-bar bg-success" style="width: 90%"></div>
                                    </div>
                                </td>
                                <td><span class="badge bg-success">90%</span>
                                </td>
                                <td><a class="btn btn-info btn-sm" href="#">
                                        <i class="fas fa-pencil-alt">
                                        </i>
                                        Edit
                                    </a>
                                    <a class="btn btn-danger btn-sm" data-toggle="modal" data-target="#modal-danger">
                                        <i class="fas fa-trash"></i>
                                        Delete
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
            </div>
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Gantt Chart</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body p-0">
                    <div id="chartDiv" style="width: 100%;height: 400px;margin: 0px auto">
                    </div>
                </div>
                <!-- /.card-body -->
            </div>
        </div>
        <!-- /.card-body -->

    </div>
    <!-- /.card -->
</section>
<script>
    var columnWidths = [120, 75, 65];
    var span = function(val, width) {
        return (
            '<span style="width:' +
            width +
            'px;">' +
            val +
            '</span>'
        );
    };
    var mapLabels = function(labels) {
        return labels
            .map(function(v, i) {
                return span(v, columnWidths[i]);
            })
            .join('');
    };

    var headerText =
        '' + mapLabels(['Task', 'Start', 'End']) + '';
    var tickTemplate = mapLabels([
        '%name',
        '%low',
        '%high'
    ]);
    boldTickTemplate = '<b>' + tickTemplate + '</b>';

    JSC.chart('chartDiv', {
        debug: true,
        /*Typical Gantt setup. Horizontal columns by default.*/
        type: 'horizontal column solid',
        /*Make columns overlap.*/
        zAxis_scale_type: 'stacked',

        defaultBox_boxVisible: false,
        defaultAnnotation: {
            label_style_fontSize: '15px'
        },
        annotations: [{
                position: '0,2',
                label_text: headerText
            },
            {
                position: 'top right',
                label_text: 'Project Beta from %min to %max'
            }
        ],
        legend: {
            position: 'inside left bottom',
            fill: 'white',
            outline_width: 0,
            corners: 'round',
            template: '%icon %name'
        },
        xAxis: {
            defaultTick: {
                label_style: {
                    fontSize: 12
                }
            }
        },
        palette: 'fiveColor46',
        yAxis: {
            id: 'yAx',
            alternateGridFill: 'none',
            scale: {
                type: 'time',
                range: ['1/1/2025', '3/31/2025']
            },
            scale_range_padding: 0.15,
            markers: [{
                    value: '1/18/2025',
                    color: 'red',
                    label_text: 'Now'
                },
                {
                    value: ['1/25/2025', '2/2/2025'],
                    color: ['gold', 0.6],
                    label_text: 'Vacation'
                }
            ]
        },
        defaultTooltip_combined: false,
        defaultPoint: {
            xAxisTick_label_text: tickTemplate,
            tooltip: '<b>%name</b> %low - %high<br/>{days(%high-%low)} days'
        },
        defaultSeries: {
            firstPoint: {
                outline: {
                    color: 'darkenMore',
                    width: 2
                },
                hatch_style: 'light-downward-diagonal',
                xAxisTick_label_text: boldTickTemplate
            }
        },
        yAxis_scale_type: 'time',
        series: [{
                name: 'Task cha 1',
                points: [{
                        name: 'Initiate Project',
                        y: ['1/1/2025', '1/31/2025']
                    },
                    {
                        name: 'Project Assignments',
                        y: ['1/1/2025', '1/15/2025']
                    },
                    {
                        name: 'Outlines/Scope',
                        y: ['1/10/2025', '1/20/2025']
                    },
                    {
                        name: 'Business Alignment',
                        y: ['1/21/2025', '1/30/2025']
                    }
                ]
            },
            {
                name: 'Task cha 2',
                points: [{
                        name: 'Plan Project',

                        y: ['2/1/2025', '2/28/2025']
                    },
                    {
                        name: 'Determine Process',
                        y: ['2/1/2025', '2/12/2025']
                    },
                    {
                        name: 'Design Layouts',
                        y: ['2/5/2025', '2/25/2025']
                    },
                    {
                        name: 'Design Structure',
                        y: ['2/20/2025', '2/28/2025']
                    }
                ]
            },
            {
                name: 'Task cha 3',
                points: [{
                        name: 'Implement Project',
                        y: ['3/1/2025', '3/31/2025']
                    },
                    {
                        name: 'Designs',
                        y: ['3/1/2025', '3/10/2025']
                    },
                    {
                        name: 'Structures',
                        y: ['3/10/2025', '3/15/2025']
                    },
                    {
                        name: 'D&S Integration',
                        y: ['3/16/2025', '3/31/2025']
                    }
                ]
            }
        ]
    });
</script>


<!-- /.content -->
@endsection