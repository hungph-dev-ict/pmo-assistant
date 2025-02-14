@extends('layouts.app')

@section('page_title')
    {{ $project->name }} - Chart
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">{{ $project->name }}</li>
@endsection

@section('inline_css')
    @vite(['resources/js/jscharting.js'])
@endsection

@section('content')
    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Gantt Chart</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div id="chartDiv" style="width: 100%; min-height: 400px; margin: 0 auto;"></div>
            </div>
            <!-- /.card-body -->
        </div>
    </section>
@endsection

@section('inline_js')
    <script>
        function countTotalTasks(tasks) {
            let count = 0;
            tasks.forEach(task => {
                count++; // Đếm task cha
                if (task.points) {
                    count += countTotalTasks(task.points); // Đếm task con
                }
            });
            return count;
        }

        var taskTree = {!! json_encode($taskTree) !!};
        var totalTasks = countTotalTasks(taskTree);

        var chartHeight = Math.max(400, totalTasks * 30 + 30) + 'px'; // Cập nhật chiều cao dựa trên tổng số task
        document.getElementById('chartDiv').style.height = chartHeight; // Gán height cho div chứa biểu đồ

        var columnWidths = [120, 75, 65];
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
                label_style_fontSize: '15px',
            },
            annotations: [{
                    position: '0,2',
                    label_text: headerText,
                },
                {
                    position: 'top right',
                    label_text: 'Project {{ $project->name }} from {{ date('n/j/Y', strtotime($minDate)) }} to {{ date('n/j/Y', strtotime($maxDate)) }}'
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
            palette: 'default',
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
                        label_text: 'Now '
                    },
                    {
                        value: ['1/25/2025', '2/2/2025'],
                        color: ['gold', 0.6],
                        label_text: 'Vacation'
                    }
                ]
            },
            chartArea: {
                height: chartHeight
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
            series: taskTree
        });
    </script>
@endsection
