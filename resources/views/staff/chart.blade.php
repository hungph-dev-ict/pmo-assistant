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
                <h3 class="card-title">Gantt Chart</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body p-0">
                <div id="chartDiv" style="width: 100%;height: 400px;margin: 0px auto">
                </div>
            </div>
            <!-- /.card-body -->
        </div>
    </section>
@endsection

@section('inline_js')
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
@endsection
