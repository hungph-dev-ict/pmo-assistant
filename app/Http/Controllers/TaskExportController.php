<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Models\Task;
use Illuminate\Support\Facades\Log;

class TaskExportController extends Controller
{
    public function export(Request $request)
    {
        // Nhận các điều kiện filter từ frontend
        $filters = $request->all();

        // Query dữ liệu với điều kiện filter
        $tasks = Task::query();

        if (!empty($filters['status'])) {
            $tasks->where('status', $filters['status']);
        }
        if (!empty($filters['priority'])) {
            $tasks->where('priority', $filters['priority']);
        }

        $tasks = $tasks->get();


        // Xuất file CSV
        $response = new StreamedResponse(function () use ($tasks) {
            $handle = fopen('php://output', 'w');

            // Ghi dòng tiêu đề
            fputcsv($handle, ['ID', 'Tên công việc', 'Trạng thái', 'Độ ưu tiên', 'Ngày tạo']);

            // Ghi từng dòng dữ liệu
            foreach ($tasks as $task) {
                fputcsv($handle, [
                    $task->id,
                    $task->name,
                    $task->status,
                    $task->priority,
                    $task->created_at,
                ]);
            }

            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="tasks.csv"');

        return $response;
    }
}
