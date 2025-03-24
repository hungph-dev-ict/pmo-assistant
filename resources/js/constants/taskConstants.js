export const TASK_TYPES = {
    EPIC: 0,
    TASK: 1,
};

export const TASK_ICONS = {
    [TASK_TYPES.EPIC]: "fas fa-bolt text-purple",
    [TASK_TYPES.TASK]: "fas fa-check-square text-blue",
};

export const TASK_STATUS = {
    OPEN: 0,
    IN_PROGRESS: 1,
    RESOLVED: 2,
    FEEDBACK: 3,
    DONE: 4,
    REOPEN: 5,
    PENDING: 6,
    CANCELED: 7,
};

export const STATUS_CLASSES = {
    [TASK_STATUS.OPEN]: "badge badge-info", // Màu xanh dương nhạt
    [TASK_STATUS.IN_PROGRESS]: "badge badge-primary", // Màu xanh dương đậm
    [TASK_STATUS.RESOLVED]: "badge badge-success", // Màu xanh lá
    [TASK_STATUS.FEEDBACK]: "badge badge-warning", // Màu vàng
    [TASK_STATUS.DONE]: "badge badge-dark", // Màu đen
    [TASK_STATUS.REOPEN]: "badge badge-danger", // Màu đỏ
    [TASK_STATUS.PENDING]: "badge badge-orange", // Màu cam
    [TASK_STATUS.CANCELED]: "badge badge-secondary", // Màu xám
};

export const statusClass = (status) => STATUS_CLASSES[status] || STATUS_CLASSES.default;

export const TASK_PRIORITY = {
    TRIVIAL: 0,
    LOWEST: 1,
    LOWER: 2,
    LOW: 3,
    MINOR: 4,
    HIGH: 5,
    HIGHER: 6,
    HIGHEST: 7,
    CRITICAL: 8,
    BLOCKER: 9,
  };
  
