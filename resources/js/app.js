import 'jquery';
import './bootstrap';

import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();

// Import AdminLTE JS
import 'admin-lte/dist/js/adminlte.min.js';
import '@fortawesome/fontawesome-free/js/all.min.js';

import { createApp } from 'vue';
import TaskContainer from '../views/components/TaskContainer.vue';

// Tìm phần tử `#task-app`
const taskAppElement = document.querySelector('#task-app');

if (taskAppElement) {
    // Tìm thẻ con `task-container` để lấy `project-id`
    const taskContainerElement = taskAppElement.querySelector('task-container');
    if (taskContainerElement) {
        const projectId = taskContainerElement.getAttribute('project-id');

        // Mount Vue với `projectId`
        createApp(TaskContainer, { projectId }).mount(taskAppElement);
    } else {
        console.error("❌ Không tìm thấy <task-container> bên trong #task-app!");
    }
} else {
    console.error("❌ Không tìm thấy #task-app trong DOM!");
}
