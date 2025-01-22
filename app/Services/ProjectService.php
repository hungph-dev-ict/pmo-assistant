<?php

namespace App\Services;

use App\Models\Project;

class ProjectService
{
    /**
     * Soft delete a project by its ID.
     */
    public function deleteProjectById(string $id): bool
    {
        $project = Project::findOrFail($id); // Tìm project theo ID, nếu không có sẽ trả về lỗi 404
        return $project->delete(); // Xóa mềm project
    }

    /**
     * Restore a soft-deleted project by its ID.
     */
    public function restoreProjectById(string $id): bool
    {
        $project = Project::withTrashed()->findOrFail($id); // Bao gồm cả project đã bị xóa mềm
        return $project->restore(); // Khôi phục project
    }

    public function createProject(array $data)
    {
        return Project::createProject($data);
    }
}
