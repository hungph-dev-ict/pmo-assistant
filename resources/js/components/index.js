import TaskContainer from "../../js/views/tasks/TaskContainer.vue";
import TaskDetail from "../../js/views/tasks/TaskDetail.vue";
import TaskList from "../../js/views/tasks/TaskList.vue";
import WorklogContainer from "../../js/views/worklogs/WorklogContainer.vue";
import WorklogManagementContainer from "../../js/views/worklogs/WorklogManagementContainer.vue";
import BulkInsertUsers from "../../js/views/bulk_insert/BulkInsertUsers.vue";
import UploadFileCreateUsers from "../../js/views/upload/UploadFileCreateUsers.vue";

export default {
    "task-container": TaskContainer,
    "task-list": TaskList,
    "task-detail": TaskDetail,
    "worklog-container": WorklogContainer,
    "bulk-insert-users": BulkInsertUsers,
    "upload-file-create-users": UploadFileCreateUsers,
    "worklog-management-container": WorklogManagementContainer,
};
