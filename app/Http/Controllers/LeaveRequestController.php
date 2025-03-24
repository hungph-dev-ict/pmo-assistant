<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use App\Models\User;
use App\Services\LeaveRequestService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class LeaveRequestController extends Controller
{
    protected $leaveRequestService;

    public function __construct(LeaveRequestService $leaveRequestService)
    {
        $this->leaveRequestService = $leaveRequestService;
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $leaveRequest = $this->leaveRequestService->createLeaveRequest($request->all());
            return response()->json(['message' => 'Leave request saved successfully!', 'leave request' => $leaveRequest], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to save leave request'], 500);
        }
    }

    public function viewLeaveRequest()
    {
        return view('leaveRequests.index');
    }

    public function getLeaveRequestType()
    {
        $myLeaveRequestTypes= $this->leaveRequestService->getLeaveRequestType();

        return response()->json($myLeaveRequestTypes, 201);
    }

    public function getLeaveRequestApprover()
    {
        $myLeaveRequestApprovers= $this->leaveRequestService->getLeaveRequestApprover();

        return response()->json($myLeaveRequestApprovers, 201);
    }

    public function getMyLeaveRequests()
    {
        $myLeaveRequests= $this->leaveRequestService->getMyLeaveRequests();

        return response()->json($myLeaveRequests, 201);
    }

    public function getLeaveRequestsManagement()
    {
        $listLeaveRequestsManagement = $this->leaveRequestService->getLeaveRequestsManagement();

        return response()->json($listLeaveRequestsManagement, 201);
    }

    public function updateLeaveRequest(Request $request, $leave_id)
    {
        try {
            $leaveRequest = LeaveRequest::findOrFail($leave_id);

            // Validate dữ liệu đầu vào
            $validatedData = $request->validate([                
                'leave_type'   => 'required', 
                'leave_date'   => 'required|date', // Kiểm tra ngày nghỉ
                'leave_start_time' => 'nullable', // Kiểm tra giờ bắt đầu nghỉ
                'leave_end_time'   => 'nullable', // Kiểm tra giờ kết thúc nghỉ
                'leave_reason' => 'nullable|string', // Kiểm tra lý do nghỉ
                'leave_approvers' => 'required|array|min:1',  // Bắt buộc phải có danh sách người duyệt
                'leave_approvers.*' => 'exists:users,id',
            ]);

            
            $leaveRequest->update($validatedData);

            $leaveRequest->approvers()->sync($validatedData['leave_approvers']);

            return response()->json([
                'message' => 'Leave request updated successfully!',
                'leave_request' => $leaveRequest,
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Leave request not found!',
                'error' => $e->getMessage(),
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update Leave request!',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function updateLeaveStatus(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'list_leave_id' => 'required|array|min:1',
                'list_leave_id.*' => 'exists:leave_requests,id',
                'leave_status' => 'required',
            ]);

            $leaveRequests = LeaveRequest::whereIn('id', $validatedData['list_leave_id'])->get();

            foreach ($leaveRequests as $leaveRequest) {
                // Cập nhật trạng thái đơn nghỉ
                $leaveRequest->update([
                    'leave_status' => $validatedData['leave_status'],
                    'leave_approver' => auth()->id(), // ID của người đang đăng nhập
                    'approved_at' => now() // Thời gian hiện tại
                ]);
            }

            return response()->json([
                'message' => 'Leave request approved successfully!',
                'leave_request' => $leaveRequest,
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Leave request not found!',
                'error' => $e->getMessage(),
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update Leave request!',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function softDeleteLeaveRequest($leave_id)
    {
        $leaveRequest = LeaveRequest::find($leave_id);

        if (!$leaveRequest) {
            return response()->json(['error' => 'Leave request not found'], 404);
        }

        $leaveRequest->delete();

        return response()->json([
            'success' => 'Leave request deleted successfully',
            'deleted_leave_request' => $leaveRequest
        ], 200);
    }
}
