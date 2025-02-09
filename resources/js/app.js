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
import TaskAdd from "../views/components/TaskAdd.vue";
import BulkInsertUsers from "../views/components/BulkInsertUsers.vue";

// 1️⃣ Mount TaskContainer nếu tồn tại
const taskListElement = document.querySelector("#task-list");
if (taskListElement) {
    const taskContainerElement =
        taskListElement.querySelector("task-container");
    if (taskContainerElement) {
        const projectId = taskContainerElement.getAttribute("project-id");
        createApp(TaskContainer, { projectId }).mount(taskListElement);
    } else {
        console.error(
            "❌ Không tìm thấy <task-container> bên trong #task-list!"
        );
    }
}

const taskAddElement = document.querySelector("task-add");
if (taskAddElement) {
    const projectId = taskAddElement.getAttribute("project-id");
    const listAssignee = taskAddElement.getAttribute("list-assignee");
    const currentUserId = taskAddElement.getAttribute("current-userid");
    createApp(TaskAdd, { projectId, listAssignee, currentUserId }).mount(taskAddElement);
} else {
    console.error("❌ Không tìm thấy <task-add>!");
}

// 2️⃣ Mount BulkInsertUsers nếu tồn tại
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
