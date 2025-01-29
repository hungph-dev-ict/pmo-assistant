@extends('layouts.app')

@section('page_title')
    {{ $project->name }} - Task List
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">{{ $project->name }} - Task List</li>
@endsection

@section('inline_css')
    @vite(['resources/js/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css', 'resources/js/plugins/datatables-responsive/css/responsive.bootstrap4.min.css', 'resources/js/plugins/datatables-buttons/css/buttons.bootstrap4.min.css'])
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">List Task</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="taskTreeTable" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Epic/Task</th>
                                    <th>Priority</th>
                                    <th>Assignee</th>
                                    <th>Status</th> <!-- Thay cột Progress thành Status -->
                                    <th>Plan Start Date</th> <!-- Thay cột Progress thành Status -->
                                    <th>Plan End Date</th> <!-- Thay cột Progress thành Status -->
                                    <th>Actual Start Date</th> <!-- Thay cột Progress thành Status -->
                                    <th>Actual End Date</th> <!-- Thay cột Progress thành Status -->
                                    <th>Estimated Effort</th>
                                    <th>Actual Effort</th>
                                    <th>Action</th>
                                    <th>Order</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php /* 
                            @forelse ($taskTree as $epic)
                            <tr class="parent-row" data-id="{{ $epic['id'] }}" style="background-color: #f8f9fa;">
                                <td>
                                    <!-- Icon mở/đóng -->
                                    <span class="toggle-icon" style="cursor: pointer; color: #007bff;">
                                        <i class="fas fa-chevron-right"></i> <!-- Icon mặc định là dấu mũi tên hướng phải -->
                                    </span>
                                    <strong>Epic:</strong> {{ $epic['name'] }}
                                </td>
                                <td>
                                    @if ($epic['priority'] == 'On Hold')
                                    <span class="badge badge-secondary">{{ $epic['priority'] ?? 'On Hold' }}</span> <!-- Màu xám cho On Hold -->
                                    @elseif ($epic['priority'] == 'Low')
                                    <span class="badge badge-info">{{ $epic['priority'] ?? 'Low' }}</span> <!-- Màu xanh lam cho Low -->
                                    @elseif ($epic['priority'] == 'Medium')
                                    <span class="badge badge-warning">{{ $epic['priority'] ?? 'Medium' }}</span> <!-- Màu vàng cho Medium -->
                                    @elseif ($epic['priority'] == 'High')
                                    <span class="badge badge-danger">{{ $epic['priority'] ?? 'High' }}</span> <!-- Màu đỏ cho High -->
                                    @elseif ($epic['priority'] == 'Critical')
                                    <span class="badge badge-dark">{{ $epic['priority'] ?? 'Critical' }}</span> <!-- Màu đen cho Critical -->
                                    @else
                                    <span class="badge badge-light">N/A</span> <!-- Trạng thái chưa xác định -->
                                    @endif
                                </td>
                                <td>
                                    <a>
                                        {{ $epic['assignee']->account }}
                                    </a>
                                    <br />
                                    <small>
                                        {{ $epic['assignee']->name }}
                                    </small>
                                <td>
                                    @if ($epic['status'] == 'Not Started')
                                    <span class="badge badge-secondary">{{ $epic['status'] ?? 'Not Started' }}</span> <!-- Màu xám cho Not Started -->
                                    @elseif ($epic['status'] == 'In Progress')
                                    <span class="badge badge-primary">{{ $epic['status'] ?? 'In Progress' }}</span> <!-- Màu xanh dương cho In Progress -->
                                    @elseif ($epic['status'] == 'Resolved')
                                    <span class="badge badge-success">{{ $epic['status'] ?? 'Resolved' }}</span> <!-- Màu xanh lá cho Resolved -->
                                    @elseif ($epic['status'] == 'Feedback')
                                    <span class="badge badge-warning">{{ $epic['status'] ?? 'Feedback' }}</span> <!-- Màu vàng cho Feedback -->
                                    @elseif ($epic['status'] == 'Done')
                                    <span class="badge badge-dark">{{ $epic['status'] ?? 'Done' }}</span> <!-- Màu đen cho Done -->
                                    @else
                                    <span class="badge badge-light">N/A</span> <!-- Trạng thái chưa xác định -->
                                    @endif
                                </td>
                                <td>{{ $epic['plan_start_date'] }}</td>
                                <td>{{ $epic['plan_end_date'] }}</td>
                                <td>{{ $epic['actual_start_date'] }}</td>
                                <td>{{ $epic['actual_end_date'] }}</td>
                                <td>{{ $epic['estimate_effort'] }}</td>
                                <td>{{ $epic['actual_effort'] }}</td>
                                <td>
                                    <a href="" class="btn btn-sm btn-primary">Edit</a>
                                    <form action="" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                    </form>
                                </td>
                            </tr>

                            <!-- Children Tasks -->
                            @foreach ($epic['children'] as $task)
                            <tr class="child-row" data-parent-id="{{ $epic['id'] }}" style="display:none; background-color: #e9ecef;">
                                <td>— Task: {{ $task['name'] }}</td>
                                <td>
                                    @if ($task['priority'] == 'On Hold')
                                    <span class="badge badge-secondary">{{ $task['priority'] ?? 'On Hold' }}</span> <!-- Màu xám cho On Hold -->
                                    @elseif ($task['priority'] == 'Low')
                                    <span class="badge badge-info">{{ $task['priority'] ?? 'Low' }}</span> <!-- Màu xanh lam cho Low -->
                                    @elseif ($task['priority'] == 'Medium')
                                    <span class="badge badge-warning">{{ $task['priority'] ?? 'Medium' }}</span> <!-- Màu vàng cho Medium -->
                                    @elseif ($task['priority'] == 'High')
                                    <span class="badge badge-danger">{{ $task['priority'] ?? 'High' }}</span> <!-- Màu đỏ cho High -->
                                    @elseif ($task['priority'] == 'Critical')
                                    <span class="badge badge-dark">{{ $task['priority'] ?? 'Critical' }}</span> <!-- Màu đen cho Critical -->
                                    @else
                                    <span class="badge badge-light">N/A</span> <!-- Trạng thái chưa xác định -->
                                    @endif
                                </td>
                                <td>
                                    <a>
                                        {{ $task['assignee']->account }}
                                    </a>
                                    <br />
                                    <small>
                                        {{ $task['assignee']->name }}
                                    </small>
                                </td>
                                <td>
                                    @if ($task['status'] == 'Not Started')
                                    <span class="badge badge-secondary">{{ $task['status'] ?? 'Not Started' }}</span> <!-- Màu xám cho Not Started -->
                                    @elseif ($task['status'] == 'In Progress')
                                    <span class="badge badge-primary">{{ $task['status'] ?? 'In Progress' }}</span> <!-- Màu xanh dương cho In Progress -->
                                    @elseif ($task['status'] == 'Resolved')
                                    <span class="badge badge-success">{{ $task['status'] ?? 'Resolved' }}</span> <!-- Màu xanh lá cho Resolved -->
                                    @elseif ($task['status'] == 'Feedback')
                                    <span class="badge badge-warning">{{ $task['status'] ?? 'Feedback' }}</span> <!-- Màu vàng cho Feedback -->
                                    @elseif ($task['status'] == 'Done')
                                    <span class="badge badge-dark">{{ $task['status'] ?? 'Done' }}</span> <!-- Màu đen cho Done -->
                                    @else
                                    <span class="badge badge-light">N/A</span> <!-- Trạng thái chưa xác định -->
                                    @endif
                                </td>
                                <td>{{ $task['plan_start_date'] }}</td>
                                <td>{{ $task['plan_end_date'] }}</td>
                                <td>{{ $task['actual_start_date'] }}</td>
                                <td>{{ $task['actual_end_date'] }}</td>
                                <td>{{ $task['estimate_effort'] }}</td>
                                <td>{{ $task['actual_effort'] }}</td>
                                <td>
                                    <a href="" class="btn btn-sm btn-primary">Edit</a>
                                    <form action="" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                            @empty
                            <tr>
                                <td colspan="4" class="text-center">No tasks found</td>
                            </tr>
                            @endforelse
                            */
                                ?>
                                @foreach ($tasks as $task)
                                    <tr @if ($task['type'] == 'epic') style="background-color: #e9ecef;" @endif>
                                        <td>{{ $task['name'] }}</td>
                                        <td>
                                            @if ($task['priority'] == 'On Hold')
                                                <span
                                                    class="badge badge-secondary">{{ $task['priority'] ?? 'On Hold' }}</span>
                                                <!-- Màu xám cho On Hold -->
                                            @elseif ($task['priority'] == 'Low')
                                                <span class="badge badge-info">{{ $task['priority'] ?? 'Low' }}</span>
                                                <!-- Màu xanh lam cho Low -->
                                            @elseif ($task['priority'] == 'Medium')
                                                <span class="badge badge-warning">{{ $task['priority'] ?? 'Medium' }}</span>
                                                <!-- Màu vàng cho Medium -->
                                            @elseif ($task['priority'] == 'High')
                                                <span class="badge badge-danger">{{ $task['priority'] ?? 'High' }}</span>
                                                <!-- Màu đỏ cho High -->
                                            @elseif ($task['priority'] == 'Critical')
                                                <span class="badge badge-dark">{{ $task['priority'] ?? 'Critical' }}</span>
                                                <!-- Màu đen cho Critical -->
                                            @else
                                                <span class="badge badge-light">N/A</span> <!-- Trạng thái chưa xác định -->
                                            @endif
                                        </td>
                                        <td>
                                            <a>
                                                {{ $task['assignee']->account }}
                                            </a>
                                            <br />
                                            <small>
                                                {{ $task['assignee']->name }}
                                            </small>
                                        </td>
                                        <td>
                                            @if ($task['status'] == 'Not Started')
                                                <span
                                                    class="badge badge-secondary">{{ $task['status'] ?? 'Not Started' }}</span>
                                                <!-- Màu xám cho Not Started -->
                                            @elseif ($task['status'] == 'In Progress')
                                                <span
                                                    class="badge badge-primary">{{ $task['status'] ?? 'In Progress' }}</span>
                                                <!-- Màu xanh dương cho In Progress -->
                                            @elseif ($task['status'] == 'Resolved')
                                                <span
                                                    class="badge badge-success">{{ $task['status'] ?? 'Resolved' }}</span>
                                                <!-- Màu xanh lá cho Resolved -->
                                            @elseif ($task['status'] == 'Feedback')
                                                <span
                                                    class="badge badge-warning">{{ $task['status'] ?? 'Feedback' }}</span>
                                                <!-- Màu vàng cho Feedback -->
                                            @elseif ($task['status'] == 'Done')
                                                <span class="badge badge-dark">{{ $task['status'] ?? 'Done' }}</span>
                                                <!-- Màu đen cho Done -->
                                            @else
                                                <span class="badge badge-light">N/A</span> <!-- Trạng thái chưa xác định -->
                                            @endif
                                        </td>
                                        <td>{{ $task['plan_start_date'] }}</td>
                                        <td>{{ $task['plan_end_date'] }}</td>
                                        <td>{{ $task['actual_start_date'] }}</td>
                                        <td>{{ $task['actual_end_date'] }}</td>
                                        <td>{{ $task['estimate_effort'] }}</td>
                                        <td>{{ $task['actual_effort'] }}</td>
                                        <td>
                                            <a href="" class="btn btn-sm btn-primary">Edit</a>
                                            <form action="" method="POST" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Are you sure?')">Delete</button>
                                            </form>
                                        </td>
                                        <td>{{ $task['display_order'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Epic/Task</th>
                                    <th>Priority</th>
                                    <th>Assignee</th>
                                    <th>Status</th> <!-- Thay Progress thành Status -->
                                    <th>Plan Start Date</th> <!-- Thay cột Progress thành Status -->
                                    <th>Plan End Date</th> <!-- Thay cột Progress thành Status -->
                                    <th>Actual Start Date</th> <!-- Thay cột Progress thành Status -->
                                    <th>Actual End Date</th> <!-- Thay cột Progress thành Status -->
                                    <th>Estimated Effort</th>
                                    <th>Actual Effort</th>
                                    <th>Action</th>
                                    <th>Order</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
@endsection


@section('inline_js')
    @vite(['resources/js/plugins/datatables/jquery.dataTables.min.js', 'resources/js/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js', 'resources/js/plugins/datatables-responsive/js/dataTables.responsive.min.js', 'resources/js/plugins/datatables-responsive/js/responsive.bootstrap4.min.js', 'resources/js/plugins/datatables-buttons/js/dataTables.buttons.min.js', 'resources/js/plugins/datatables-buttons/js/buttons.bootstrap4.min.js', 'resources/js/plugins/jszip/jszip.min.js'])
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.4/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.4/vfs_fonts.js"></script>
    @vite(['resources/js/plugins/datatables-buttons/js/buttons.html5.min.js', 'resources/js/plugins/datatables-buttons/js/buttons.print.min.js', 'resources/js/plugins/datatables-buttons/js/buttons.colVis.min.js'])
@endsection

@section('custom_inline_js')
    <script>
        $(function() {
            $("#taskTreeTable").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf", "print",
                    {
                        extend: "colvis", // Tạo button cho column visibility
                        columns: ':not(:eq(11))' // Không hiển thị cột thứ 11 (cột `display_order`)
                    }, {
                        // Tạo nút custom "Reset Sort"
                        text: 'Reset Sort',
                        action: function(e, dt, node, config) {
                            dt.order([11, 'asc']).draw();
                        }
                    }
                ],
                "paging": false, // Không phân trang
                "order": [
                    [11, 'asc']
                ], // Tắt sắp xếp mặc định
                "columnDefs": [{
                        "targets": [4, 5, 6, 7, 8, 9], // Ẩn cột date và progress khi load trang
                        "visible": false
                    },
                    {
                        "targets": [11], // Ẩn cột display_order
                        "visible": false
                    }
                ],
                "stateSave": false, // Lưu trạng thái của DataTable,
            }).buttons().container().appendTo('#taskTreeTable_wrapper .col-md-6:eq(0)');

            // $('#resetSortBtn').click(function() {
            //     $('#taskTreeTable').replaceWith(tableHtml);
            // });

            // Toggle Epic-Task visibility và thay đổi icon
            /*$('#taskTreeTable').on('click', '.toggle-icon', function() {
                var parentRow = $(this).closest('tr');
                var parentId = parentRow.data('id');
                var childRows = $(".child-row[data-parent-id='" + parentId + "']");

                // Nếu các task con đang hiển thị, ẩn chúng và thay đổi icon
                if (childRows.is(':visible')) {
                    childRows.hide();
                    $(this).html('<i class="fas fa-chevron-right"></i>'); // Thay đổi icon
                } else {
                    childRows.show();
                    $(this).html('<i class="fas fa-chevron-down"></i>'); // Thay đổi icon
                }
            });*/
        });
    </script>
@endsection
