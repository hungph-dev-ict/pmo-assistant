import "jquery";
import "./bootstrap";

import Alpine from "alpinejs";
window.Alpine = Alpine;
Alpine.start();

// Import AdminLTE JS
import "admin-lte/dist/js/adminlte.min.js";
import "@fortawesome/fontawesome-free/js/all.min.js";

import { createApp } from "vue";
import TaskContainer from "../views/components/TaskContainer.vue";
import WorklogContainer from "../views/components/WorklogContainer.vue";
import BulkInsertUsers from "../views/components/BulkInsertUsers.vue";
import UploadFileCreateUsers from "../views/components/UploadFileCreateUsers.vue";

const taskListElement = document.querySelector("#task-list");
if (taskListElement) {
    const taskContainerElement =
        taskListElement.querySelector("task-container");
    if (taskContainerElement) {
        const projectId = taskContainerElement.getAttribute("project-id");
        const listAssignee = taskContainerElement.getAttribute("list-assignee");
        const currentUserId = taskContainerElement.getAttribute("current-userid");
        const userRole = taskContainerElement.getAttribute("user-role");
        createApp(TaskContainer, { projectId, listAssignee, currentUserId, userRole }).mount(taskListElement);
    } else {
        console.error(
            "❌ Không tìm thấy <task-container> bên trong #task-list!"
        );
    }
}

const worklogListElement = document.querySelector("#worklog-list");
if (worklogListElement) {
    const worklogContainerElement =
    worklogListElement.querySelector("worklog-container");
    if (worklogContainerElement) {
        // const projectId = worklogContainerElement.getAttribute("project-id");
        // const listAssignee = worklogContainerElement.getAttribute("list-assignee");
        // const currentUserId = worklogContainerElement.getAttribute("current-userid");
        // const userRole = worklogContainerElement.getAttribute("user-role");
        // createApp(TaskContainer, { projectId, listAssignee, currentUserId, userRole }).mount(worklogListElement);
        createApp(WorklogContainer, {  }).mount(worklogListElement);

    } else {
        console.error(
            "❌ Không tìm thấy <task-container> bên trong #task-list!"
        );
    }
}

// const taskAddElement = document.querySelector("task-add");
// if (taskAddElement) {
//     const projectId = taskAddElement.getAttribute("project-id");
//     const listAssignee = taskAddElement.getAttribute("list-assignee");
//     const currentUserId = taskAddElement.getAttribute("current-userid");
//     createApp(TaskAdd, { projectId, listAssignee, currentUserId }).mount(taskAddElement);
// }

const bulkInsertElement = document.querySelector("#bulk-insert-element");
if (bulkInsertElement) {
    const submitUrl = bulkInsertElement
        .querySelector("bulk-insert-users")
        ?.getAttribute("submit-url");

    if (submitUrl) {
        createApp(BulkInsertUsers, { submitUrl }).mount(bulkInsertElement);
    } else {
        console.error(
            "❌ Không tìm thấy thuộc tính submit-url trên <bulk-insert-users>!"
        );
    }
}

const upfileCreateUsersElement = document.querySelector("#upfile-create-users-element");
if (upfileCreateUsersElement) {
    const submitUrl = upfileCreateUsersElement
        .querySelector("upfile-create-users")
        ?.getAttribute("submit-url");

    if (submitUrl) {
        createApp(UploadFileCreateUsers, { submitUrl }).mount(upfileCreateUsersElement);
    } else {
        console.error(
            "❌ Không tìm thấy thuộc tính submit-url trên <upfile-create-users-element>!"
        );
    }
}

import axios from "axios";

// Lấy CSRF token từ meta tag trong Blade
const token = document
    .querySelector('meta[name="csrf-token"]')
    ?.getAttribute("content");

if (token) {
    axios.defaults.headers.common["X-CSRF-TOKEN"] = token;
} else {
    console.error("CSRF token không tìm thấy!");
}
