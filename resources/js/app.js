import { createApp } from "vue";
import components from "./components"; // Import danh sách components
import "jquery";
import "./bootstrap";
import Alpine from "alpinejs";
import axios from "axios";
import "admin-lte/dist/js/adminlte.min.js";
import "@fortawesome/fontawesome-free/js/all.min.js";
import PriorityIcon from "../js/common/PriorityIcon.vue";

// Khởi động AlpineJS
window.Alpine = Alpine;
Alpine.start();

// Set CSRF token cho Axios
const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute("content");
if (token) {
    axios.defaults.headers.common["X-CSRF-TOKEN"] = token;
} else {
    console.error("CSRF token không tìm thấy!");
}

const app = createApp({});

// Đăng ký toàn cục các component trong `components`
Object.entries(components).forEach(([name, component]) => {
    app.component(name, component);
});

// 🔥 Đăng ký PriorityIcon toàn cục
app.component("PriorityIcon", PriorityIcon);

// Mount tự động theo ID
document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll("[data-vue-app]").forEach((el) => {
        const componentName = el.dataset.vueApp;

        if (components[componentName]) {
            const props = Object.fromEntries(
                Array.from(el.attributes)
                    .filter(attr => attr.name.startsWith("data-"))
                    .map(attr => [attr.name.replace("data-", ""), attr.value])
            );
            createApp(components[componentName], props)
                .component("PriorityIcon", PriorityIcon) // Đăng ký PriorityIcon trong từng app nhỏ
                .mount(el);
        }
    });
});
