@extends('layouts.app')

@section('page_title')
    Gantt Chart
@endsection

@section('inline_css')
    @vite(['resources/js/jscharting.js'])
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
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Gantt Chart</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div id="chartDiv" style="width: 100%;height: 400px;margin: 0px auto">
                </div>
            </div>
            <!-- /.card-body -->
        </div>
    </section>
@endsection

@section('inline_js')
    <script>
        var tasks = {!! json_encode($taskData) !!};

        var columnWidths = [140, 80, 80];
        var span = function(val, width) {
            return '<span style="display: inline-block; width:' + width + 'px; text-align: center;">' + val + '</span>';
        };
        var mapLabels = function(labels) {
            return labels.map((v, i) => span(v, columnWidths[i])).join('');
        };

        var headerText = mapLabels(['Task', 'Start', 'End']);
        var tickTemplate = mapLabels(['%name', '%low', '%high']);
        var boldTickTemplate = '<b>' + tickTemplate + '</b>';

        JSC.chart('chartDiv', {
            debug: true,
            type: 'horizontal column solid',
            zAxis_scale_type: 'stacked',
            defaultBox_boxVisible: false,
            defaultAnnotation: {
                label_style_fontSize: '14px',
                verticalAlign: 'middle'
            },
            annotations: [{
                    position: '0,3',
                    label_text: headerText,
                    label_style_fontWeight: 'bold',
                    label_style_fontSize: '16px'
                },
                {
                    position: 'top right',
                    label_text: 'Project from {{ date('n/j/Y', strtotime($minDate)) }} to {{ date('n/j/Y', strtotime($maxDate)) }}'
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
                    range: ['{{ date('n/j/Y', strtotime($minDate)) }}', '{{ date('n/j/Y', strtotime($maxDate)) }}']
                },
                scale_range_padding: 0.15,
                markers: [{
                    value: '{{ date('n/j/Y') }}',
                    color: 'red',
                    label_text: 'Now'
                }]
            },
            defaultTooltip_combined: false,
            defaultPoint: {
                xAxisTick_label_text: tickTemplate,
                tooltip: '<b>%name</b><br/> %low - %high<br/> {days(%high-%low)} days'
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
                name: 'Tasks',
                points: tasks
            }]
        });
    </script>
@endsection
