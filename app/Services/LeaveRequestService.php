<?php

namespace App\Services;

use App\Models\User;
use App\Models\Constant;
use App\Models\LeaveRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class LeaveRequestService
{

    public function getLeaveRequestsManagement()
    {
        $user = Auth::user();

        // Kiểm tra xem người dùng có quyền 'pm' (project manager)
        if (!$user || !$user->hasAnyRole(['pm', 'client'])) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Lọc các LeaveRequest của user tham gia các dự án mà project manager này quản lý
        $leaveRequests = LeaveRequest::whereHas('approvers', function ($query) use ($user) {
            $query->where('leave_approver', $user->id); // Kiểm tra nếu user là Approver
        })
        ->with(['user', 'leaveRequestType', 'leaveRequestStatus', 'approvers', 'approver']) 
        ->orderBy('leave_status')
        ->orderBy('created_at', 'desc') // Sắp xếp theo thời gian tạo đơn nghỉ
        ->get();

        return response()->json([
            'success' => true,
            'data' => $leaveRequests
        ]);
    }

    public function getMyLeaveRequests()
    {
        $user = Auth::user(); // Lấy người dùng đang đăng nhập

        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401); // Nếu không có người dùng thì trả về lỗi
        }

        // Lọc các LeaveRequest theo user_id và sắp xếp theo thời gian tạo
        $leaveRequests = LeaveRequest::where('leave_user', $user->id)
            ->with(['user', 'leaveRequestType', 'leaveRequestStatus', 'approvers', 'approver']) 
            ->orderBy('created_at', 'desc') // Sắp xếp theo thời gian tạo đơn nghỉ
            ->get();

        return response()->json([
            'success' => true,
            'data' => $leaveRequests // Trả về danh sách leave requests của người dùng
        ]);
    }

    public function getLeaveRequestType()
    {
        // Lọc các LeaveRequest theo user_id và sắp xếp theo thời gian tạo
        $leaveTypes = Constant::where('group', 'leave_request_type')->get();

        return response()->json([
            'success' => true,
            'data' => $leaveTypes // Trả về danh sách leave requests của người dùng
        ]);
    }

    public function getLeaveRequestApprover()
    {
        // Lọc các LeaveRequest theo user_id và sắp xếp theo thời gian tạo
        $listLeaveRequestApprover  = User::role(['pm', 'client'])->where('tenant_id', auth()->user()->tenant_id)->get();

        return response()->json([
            'success' => true,
            'data' => $listLeaveRequestApprover // Trả về danh sách leave requests của người dùng
        ]);
    }



    public function createLeaveRequest($data)
    {
        // Validate dữ liệu
        $validator = Validator::make($data, [
            'leave_user'   => 'required|exists:users,id',  
            'leave_type'   => 'required', 
            'leave_date'   => 'required|date', 
            'leave_start_time' => 'nullable', 
            'leave_end_time'   => 'nullable', 
            'leave_reason' => 'nullable|string', 
            'leave_approvers' => 'required|array|min:1',  // Bắt buộc phải có danh sách người duyệt
            'leave_approvers.*' => 'exists:users,id', // Mỗi phần tử trong danh sách phải là ID hợp lệ của user
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $leaveRequest = LeaveRequest::create([
            'leave_user'   => $data['leave_user'],
            'leave_type'   => $data['leave_type'],
            'leave_date'   => $data['leave_date'],
            'leave_start_time' => $data['leave_start_time'] ?? null, 
            'leave_end_time'   => $data['leave_end_time'] ?? null, 
            'leave_reason' => $data['leave_reason'] ?? null, 
        ]);

        $leaveRequest->approvers()->sync($data['leave_approvers']);

        return $leaveRequest; 
    }

    
}
