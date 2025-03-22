<template>
    <div class="row">
        <div class="col-12">
            <div class="card card-primary">
                <div class="card-header d-flex p-0">
                    <h3 class="card-title p-3">List Task</h3>
                    <ul class="nav nav-pills ml-auto p-2">
                        <li class="nav-item">
                            <a
                                class="nav-link active"
                                href="#list"
                                data-toggle="tab"
                                >List</a
                            >
                        </li>
                        <li class="nav-item">
                            <a
                                class="nav-link"
                                href="#history"
                                data-toggle="tab"
                                >Recently Activities</a
                            >
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#audit" data-toggle="tab"
                                >Audit</a
                            >
                        </li>
                        <button
                            type="button"
                            class="btn btn-tool"
                            data-card-widget="collapse"
                            title="Collapse"
                        >
                            <i class="fas fa-minus"></i>
                        </button>
                    </ul>
                </div>

                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane" id="history">
                            <div v-if="projectWorklogIsLoading" class="overlay">
                                <div class="spinner"></div>
                                <p>Loading...</p>
                            </div>
                            <div class="row">
                                <div class="col-8">
                                    <p class="lead">Worklog Activities</p>

                                    <div
                                        v-for="worklog in projecWorklogs"
                                        :key="worklog.id"
                                    >
                                        <p>
                                            <strong
                                                >{{ worklog.user.name }} ({{
                                                    worklog.user.account
                                                }})</strong
                                            >
                                            at
                                            {{
                                                new Date(worklog.created_at)
                                                    .toISOString()
                                                    .slice(0, 16)
                                                    .replace("T", " ")
                                            }}
                                            <!-- /.user-block -->

                                            logged into:
                                            <strong>{{
                                                worklog.task.name
                                            }}</strong>
                                            . Time logged:
                                            <strong
                                                :style="{
                                                    color:
                                                        worklog.log_time > 4
                                                            ? 'red'
                                                            : 'inherit',
                                                }"
                                            >
                                                {{ worklog.log_time }}
                                            </strong>
                                            hours.

                                            <span v-if="worklog.description"
                                                >Work description:
                                                {{ worklog.description }}</span
                                            >
                                            <strong style="color: red" v-else
                                                >No work description
                                                provided.</strong
                                            >
                                        </p>
                                    </div>
                                </div>

                                <div class="col-4">
                                    <p class="lead">Users Without Worklog</p>

                                    <div
                                        v-for="(users, date) in groupedUsers"
                                        :key="date"
                                        class="post"
                                    >
                                        <p>
                                            <strong>Ngày {{ date }}</strong>
                                        </p>
                                        <p>
                                            The users have not logged their
                                            work:
                                            <span style="color: red">{{
                                                users.join(", ")
                                            }}</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane active" id="list">
                            <table
                                class="table table-sm fixed-header-table table-bordered"
                                style="margin-right: 20px"
                            >
                                <thead>
                                    <tr>
                                        <th
                                            style="width: 3%"
                                            class="text-center"
                                        >
                                            Key
                                        </th>
                                        <th
                                            style="width: 2.5%"
                                            class="text-center"
                                        >
                                            Type
                                        </th>
                                        <th
                                            v-if="isColumnVisible('epic_task')"
                                            class="text-center"
                                            style="width: 21.5%"
                                        >
                                            Summary
                                        </th>
                                        <th
                                            v-if="isColumnVisible('priority')"
                                            class="text-center"
                                            style="width: 5%"
                                        >
                                            Priority
                                        </th>
                                        <th
                                            v-if="isColumnVisible('assignee')"
                                            class="text-center"
                                            style="width: 6%"
                                        >
                                            Assignee
                                        </th>
                                        <th
                                            v-if="
                                                isColumnVisible(
                                                    'plan_start_date'
                                                )
                                            "
                                            style="width: 8%"
                                            class="text-center"
                                        >
                                            Plan Start Date
                                        </th>
                                        <th
                                            v-if="
                                                isColumnVisible('plan_end_date')
                                            "
                                            style="width: 8%"
                                            class="text-center"
                                        >
                                            Plan End Date
                                        </th>
                                        <th
                                            v-if="
                                                isColumnVisible(
                                                    'actual_start_date'
                                                )
                                            "
                                            style="width: 8%"
                                            class="text-center"
                                        >
                                            Actual Start Date
                                        </th>
                                        <th
                                            v-if="
                                                isColumnVisible(
                                                    'actual_end_date'
                                                )
                                            "
                                            style="width: 8%"
                                            class="text-center"
                                        >
                                            Actual End Date
                                        </th>
                                        <th
                                            v-if="
                                                isColumnVisible('plan-effort')
                                            "
                                            style="width: 5%"
                                            class="text-center"
                                        >
                                            Plan Effort
                                        </th>
                                        <th
                                            v-if="
                                                isColumnVisible('actual-effort')
                                            "
                                            style="width: 5%"
                                            class="text-center"
                                        >
                                            Actual Effort
                                        </th>
                                        <th
                                            v-if="isColumnVisible('status')"
                                            style="width: 6%"
                                            class="text-center"
                                        >
                                            Status
                                        </th>
                                        <th
                                            class="text-center"
                                            v-if="isColumnVisible('action')"
                                            style="width: 12%"
                                        >
                                            Action
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr
                                        class="bg-light"
                                        v-for="task in taskListData.tasks"
                                        :key="task.id"
                                        v-show="
                                            task.type == TASK_TYPES.EPIC ||
                                            isExpanded(task.parent_id)
                                        "
                                    >
                                        <td class="text-center">
                                            {{ task.id }}
                                        </td>
                                        <td class="text-center">
                                            <i
                                                :class="TASK_ICONS[task.type]"
                                            ></i>
                                        </td>
                                        <td v-if="isColumnVisible('epic_task')">
                                            <!-- Nút toggle cho Epic -->
                                            <template
                                                v-if="
                                                    !task.isEditing ||
                                                    (task.isEditing &&
                                                        !hasPermissionClient &&
                                                        !hasPermissionPm &&
                                                        hasPermissionStaff)
                                                "
                                            >
                                                <span
                                                    v-if="
                                                        task.type ===
                                                        TASK_TYPES.EPIC
                                                    "
                                                    @click="toggleTask(task.id)"
                                                    class="cursor-pointer mr-2"
                                                >
                                                    <span
                                                        v-if="
                                                            !isExpanded(task.id)
                                                        "
                                                        class="triangle-right"
                                                    ></span>
                                                    <span
                                                        v-else
                                                        class="triangle-down"
                                                    ></span>
                                                </span>

                                                <!-- Hiển thị tên task -->
                                                <a
                                                    :href="`/${props.projectId}/task/${task.id}`"
                                                >
                                                    {{ task.name }}
                                                </a>
                                            </template>

                                            <input
                                                v-else
                                                type="text"
                                                v-model="task.editedName"
                                                class="form-control"
                                            />
                                        </td>
                                        <td
                                            style="width: 5%"
                                            v-if="isColumnVisible('priority')"
                                            class="text-center"
                                        >
                                            <PriorityIcon
                                                v-if="
                                                    !task.isEditing ||
                                                    (task.isEditing &&
                                                        !hasPermissionClient &&
                                                        !hasPermissionPm &&
                                                        hasPermissionStaff)
                                                "
                                                :priority="task.priority"
                                            />
                                            <select
                                                v-else
                                                class="form-control priority-select"
                                                v-model="task.editedPriority"
                                            >
                                                <option
                                                    v-for="[
                                                        priority,
                                                        id,
                                                    ] in Object.entries(
                                                        listPriorities
                                                    )"
                                                    :key="id"
                                                    :value="id"
                                                >
                                                    {{ priority }}
                                                </option>
                                            </select>
                                        </td>
                                        <td
                                            style="width: 6%"
                                            v-if="isColumnVisible('assignee')"
                                            class="text-center"
                                        >
                                            <span
                                                v-if="
                                                    !task.isEditing ||
                                                    (task.isEditing &&
                                                        !hasPermissionClient &&
                                                        !hasPermissionPm &&
                                                        hasPermissionStaff)
                                                "
                                            >
                                                <strong
                                                    v-if="
                                                        task.assignee_user
                                                            ?.account ==
                                                        currentUserAccount
                                                    "
                                                >
                                                    {{
                                                        task.assignee_user
                                                            ?.account
                                                    }}
                                                </strong>
                                                <template v-else>
                                                    {{
                                                        task.assignee_user
                                                            ?.account
                                                    }}
                                                </template>
                                            </span>

                                            <select
                                                v-else
                                                class="form-control assignee-select"
                                                v-model="task.editedAssignee"
                                            >
                                                <option
                                                    v-for="[
                                                        account,
                                                        id,
                                                    ] in Object.entries(
                                                        listAssignee
                                                    )"
                                                    :key="id"
                                                    :value="id"
                                                >
                                                    {{ account }}
                                                </option>
                                            </select>
                                        </td>

                                        <td
                                            style="width: 8%"
                                            class="text-center"
                                            v-if="
                                                isColumnVisible(
                                                    'plan_start_date'
                                                )
                                            "
                                        >
                                            <span
                                                v-if="
                                                    !task.isEditing ||
                                                    (task.isEditing &&
                                                        !hasPermissionClient &&
                                                        !hasPermissionPm &&
                                                        hasPermissionStaff)
                                                "
                                                ><span
                                                    :style="{
                                                        color: task.delayed
                                                            ? 'red'
                                                            : 'inherit',
                                                    }"
                                                >
                                                    {{ task.plan_start_date }}
                                                    <span v-if="task.delayed"
                                                        >🔥</span
                                                    >
                                                </span></span
                                            >
                                            <div
                                                v-else
                                                class="input-group date plan-start-datepicker"
                                                data-target-input="nearest"
                                            >
                                                <input
                                                    type="text"
                                                    class="form-control datetimepicker-input"
                                                    v-model="
                                                        task.editedPlanStartDate
                                                    "
                                                    data-target=".plan-start-datepicker"
                                                />
                                                <div
                                                    class="input-group-append"
                                                    data-target=".plan-start-datepicker"
                                                    data-toggle="datetimepicker"
                                                >
                                                    <div
                                                        class="input-group-text"
                                                    >
                                                        <i
                                                            class="fa fa-calendar"
                                                        ></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>

                                        <td
                                            style="width: 8%"
                                            class="text-center"
                                            v-if="
                                                isColumnVisible('plan_end_date')
                                            "
                                        >
                                            <span
                                                v-if="
                                                    !task.isEditing ||
                                                    (task.isEditing &&
                                                        !hasPermissionClient &&
                                                        !hasPermissionPm &&
                                                        hasPermissionStaff)
                                                "
                                            >
                                                <span
                                                    :style="{
                                                        color: task.overdue
                                                            ? 'red'
                                                            : 'inherit',
                                                    }"
                                                >
                                                    {{ task.plan_end_date }}
                                                    <span v-if="task.overdue"
                                                        >🔥</span
                                                    >
                                                </span>
                                            </span>
                                            <div
                                                v-else
                                                class="input-group date plan-end-datepicker"
                                                data-target-input="nearest"
                                            >
                                                <input
                                                    type="text"
                                                    class="form-control datetimepicker-input"
                                                    v-model="
                                                        task.editedPlanEndDate
                                                    "
                                                    data-target=".plan-end-datepicker"
                                                />
                                                <div
                                                    class="input-group-append"
                                                    data-target=".plan-end-datepicker"
                                                    data-toggle="datetimepicker"
                                                >
                                                    <div
                                                        class="input-group-text"
                                                    >
                                                        <i
                                                            class="fa fa-calendar"
                                                        ></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>

                                        <td
                                            style="width: 8%"
                                            class="text-center"
                                            v-if="
                                                isColumnVisible(
                                                    'actual_start_date'
                                                )
                                            "
                                        >
                                            <span
                                                v-if="
                                                    !task.isEditing ||
                                                    (task.isEditing &&
                                                        !hasPermissionClient &&
                                                        !hasPermissionPm &&
                                                        !hasPermissionStaff)
                                                "
                                                >{{
                                                    task.actual_start_date
                                                }}</span
                                            >
                                            <div
                                                v-else
                                                class="input-group date actual-start-datepicker"
                                                data-target-input="nearest"
                                            >
                                                <input
                                                    type="text"
                                                    class="form-control datetimepicker-input"
                                                    v-model="
                                                        task.editedActualStartDate
                                                    "
                                                    data-target=".actual-start-datepicker"
                                                />
                                                <div
                                                    class="input-group-append"
                                                    data-target=".actual-start-datepicker"
                                                    data-toggle="datetimepicker"
                                                >
                                                    <div
                                                        class="input-group-text"
                                                    >
                                                        <i
                                                            class="fa fa-calendar"
                                                        ></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>

                                        <td
                                            style="width: 8%"
                                            class="text-center"
                                            v-if="
                                                isColumnVisible(
                                                    'actual_end_date'
                                                )
                                            "
                                        >
                                            <span
                                                v-if="
                                                    !task.isEditing ||
                                                    (task.isEditing &&
                                                        !hasPermissionClient &&
                                                        !hasPermissionPm &&
                                                        !hasPermissionStaff)
                                                "
                                                >{{
                                                    task.actual_end_date
                                                }}</span
                                            >
                                            <div
                                                v-else
                                                class="input-group date actual-end-datepicker"
                                                data-target-input="nearest"
                                            >
                                                <input
                                                    type="text"
                                                    class="form-control datetimepicker-input"
                                                    v-model="
                                                        task.editedActualEndDate
                                                    "
                                                    data-target=".actual-end-datepicker"
                                                />
                                                <div
                                                    class="input-group-append"
                                                    data-target=".actual-end-datepicker"
                                                    data-toggle="datetimepicker"
                                                >
                                                    <div
                                                        class="input-group-text"
                                                    >
                                                        <i
                                                            class="fa fa-calendar"
                                                        ></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>

                                        <td
                                            style="width: 3%"
                                            v-if="
                                                isColumnVisible('plan-effort')
                                            "
                                            class="text-right"
                                        >
                                            <span
                                                v-if="
                                                    !task.isEditing ||
                                                    (task.isEditing &&
                                                        !hasPermissionClient &&
                                                        !hasPermissionPm &&
                                                        hasPermissionStaff)
                                                "
                                                >{{ task.plan_effort }}</span
                                            >
                                            <input
                                                v-else
                                                type="number"
                                                v-model="task.editedPlanEffort"
                                                class="form-control no-spinner"
                                            />
                                        </td>

                                        <td
                                            style="width: 3%"
                                            v-if="
                                                isColumnVisible('actual-effort')
                                            "
                                            class="text-right"
                                        >
                                            {{
                                                Number(task.actual_effort) > 0
                                                    ? task.actual_effort
                                                    : ""
                                            }}<i
                                                v-if="task.overcost"
                                                class="fas fa-exclamation-triangle text-danger ml-2"
                                                title="Actual effort exceeds plan effort"
                                            ></i>
                                        </td>

                                        <td
                                            style="width: 6%"
                                            v-if="isColumnVisible('status')"
                                            class="text-center"
                                        >
                                            <span
                                                v-if="!task.isEditing"
                                                :class="
                                                    statusClass(task.status)
                                                "
                                                >{{
                                                    task.task_status?.value1
                                                }}</span
                                            >
                                            <select
                                                v-else
                                                class="form-control status-select"
                                                v-model="task.editedStatus"
                                            >
                                                <option
                                                    v-for="[
                                                        status,
                                                        id,
                                                    ] in Object.entries(
                                                        listStatuses
                                                    )"
                                                    :key="id"
                                                    :value="id"
                                                >
                                                    {{ status }}
                                                </option>
                                            </select>
                                        </td>
                                        <td
                                            style="width: 10%"
                                            v-if="isColumnVisible('action')"
                                            class="text-left"
                                        >
                                            <template v-if="!task.isEditing">
                                                <a
                                                    href="#"
                                                    class="btn btn-info btn-sm ml-3 mr-2"
                                                    @click.prevent="
                                                        editTask(task)
                                                    "
                                                    v-tooltip="'Edit'"
                                                >
                                                    <i
                                                        class="fas fa-pencil-alt"
                                                    ></i>
                                                </a>
                                                <a
                                                    v-if="
                                                        hasPermissionClient ||
                                                        hasPermissionPm
                                                    "
                                                    href="#"
                                                    class="btn btn-danger btn-sm mr-2"
                                                    @click="confirmDelete(task)"
                                                    v-tooltip="'Delete'"
                                                >
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                                <button
                                                    class="btn btn-secondary btn-sm mr-2"
                                                    @click="copyTaskLink(task)"
                                                    v-tooltip="'Share'"
                                                >
                                                    <i class="fas fa-link"></i>
                                                </button>
                                                <a
                                                    href="#"
                                                    class="btn btn-primary btn-sm"
                                                    v-if="
                                                        ![
                                                            TASK_STATUS.OPEN,
                                                            TASK_STATUS.DONE,
                                                            TASK_STATUS.REOPEN,
                                                            TASK_STATUS.PENDING,
                                                            TASK_STATUS.CANCELED,
                                                        ].includes(task.status)
                                                    "
                                                    @click.prevent="
                                                        openLogWorkModal(task)
                                                    "
                                                    v-tooltip="'Log Work'"
                                                >
                                                    <i class="fas fa-clock"></i>
                                                </a>
                                            </template>

                                            <template v-else>
                                                <a
                                                    href="#"
                                                    class="btn btn-success btn-sm ml-3 mr-2"
                                                    @click.prevent="
                                                        updateTask(task)
                                                    "
                                                    v-tooltip="'Update'"
                                                >
                                                    <i class="fas fa-save"></i>
                                                </a>
                                                <a
                                                    href="#"
                                                    class="btn btn-secondary btn-sm"
                                                    @click.prevent="
                                                        cancelEdit(task)
                                                    "
                                                    v-tooltip="'Discard'"
                                                >
                                                    <i class="fas fa-times"></i>
                                                </a>
                                            </template>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane" id="audit">
                            <div class="row">
                                <div class="col-3">
                                    <p class="lead">Base Audit</p>

                                    <div class="table-responsive">
                                        <table class="table">
                                            <tbody>
                                                <tr>
                                                    <th
                                                        style="width: 50%"
                                                        v-tooltip="
                                                            'Tổng effort đã lập kế hoạch (PV) tính theo giờ, MD, MM'
                                                        "
                                                    >
                                                        Reported Plan Effort:
                                                    </th>
                                                    <td>
                                                        {{
                                                            props.projectAudit
                                                                .reported_plan_effort
                                                                .hours
                                                        }}
                                                        h /
                                                        {{
                                                            props.projectAudit
                                                                .reported_plan_effort
                                                                .md
                                                        }}
                                                        MD /
                                                        {{
                                                            props.projectAudit
                                                                .reported_plan_effort
                                                                .mm
                                                        }}
                                                        MM
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th
                                                        v-tooltip="
                                                            'Tổng effort thực tế đã tiêu tốn (AC) tính theo giờ, MD, MM'
                                                        "
                                                    >
                                                        Reported Actual Effort:
                                                    </th>
                                                    <td>
                                                        {{
                                                            props.projectAudit
                                                                .reported_actual_effort
                                                                .hours
                                                        }}
                                                        h /
                                                        {{
                                                            props.projectAudit
                                                                .reported_actual_effort
                                                                .md
                                                        }}
                                                        MD /
                                                        {{
                                                            props.projectAudit
                                                                .reported_actual_effort
                                                                .mm
                                                        }}
                                                        MM
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th
                                                        v-tooltip="
                                                            'Giá trị kế hoạch (PV) – tổng effort kế hoạch của các task có ngày kết thúc <= hôm nay'
                                                        "
                                                    >
                                                        Plan Value (PV):
                                                    </th>
                                                    <td>
                                                        {{
                                                            props.projectAudit
                                                                .planned_value
                                                        }}
                                                        h
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th
                                                        v-tooltip="
                                                            'Chi phí thực tế (AC) – tổng effort thực tế của các task đã hoàn thành'
                                                        "
                                                    >
                                                        Actual Cost (AC):
                                                    </th>
                                                    <td>
                                                        {{
                                                            props.projectAudit
                                                                .actual_cost
                                                        }}
                                                        h
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th
                                                        v-tooltip="
                                                            'Giá trị đạt được (EV) – tổng effort kế hoạch của các task đã hoàn thành'
                                                        "
                                                    >
                                                        Earned Value (EV):
                                                    </th>
                                                    <td>
                                                        {{
                                                            props.projectAudit
                                                                .earned_value
                                                        }}
                                                        h
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="col-3">
                                    <p class="lead">Performance Metrics</p>

                                    <div class="table-responsive">
                                        <table class="table">
                                            <tbody>
                                                <tr>
                                                    <th
                                                        v-tooltip="
                                                            'Chỉ số hiệu suất chi phí (CPI) = EV / AC. CPI > 1 nghĩa là chi phí hiệu quả.'
                                                        "
                                                    >
                                                        Cost Performance Index
                                                        (CPI):
                                                    </th>
                                                    <td>
                                                        {{
                                                            props.projectAudit
                                                                .cpi
                                                        }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th
                                                        v-tooltip="
                                                            'Chỉ số hiệu suất tiến độ (SPI) = EV / PV. SPI > 1 nghĩa là tiến độ tốt.'
                                                        "
                                                    >
                                                        Schedule Performance
                                                        Index (SPI):
                                                    </th>
                                                    <td>
                                                        {{
                                                            props.projectAudit
                                                                .spi
                                                        }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th
                                                        v-tooltip="
                                                            'Chênh lệch tiến độ (SV) = EV - PV. SV > 0 nghĩa là dự án đang vượt tiến độ.'
                                                        "
                                                    >
                                                        Schedule Variance (SV):
                                                    </th>
                                                    <td>
                                                        {{
                                                            props.projectAudit
                                                                .sv
                                                        }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th
                                                        v-tooltip="
                                                            'Chênh lệch chi phí (CV) = EV - AC. CV > 0 nghĩa là chi phí đang được kiểm soát tốt.'
                                                        "
                                                    >
                                                        Cost Variance (CV):
                                                    </th>
                                                    <td>
                                                        {{
                                                            props.projectAudit
                                                                .cv
                                                        }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th
                                                        v-tooltip="
                                                            'Tổng ngân sách dự án khi hoàn thành (BAC) – tổng effort kế hoạch ban đầu.'
                                                        "
                                                    >
                                                        Budget At Completion
                                                        (BAC):
                                                    </th>
                                                    <td>
                                                        {{
                                                            props.projectAudit
                                                                .bac
                                                        }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th
                                                        v-tooltip="
                                                            'Ước tính tổng chi phí hoàn thành dự án (EAC) = BAC / CPI.'
                                                        "
                                                    >
                                                        Estimate At Completion
                                                        (EAC):
                                                    </th>
                                                    <td>
                                                        {{
                                                            props.projectAudit
                                                                .eac
                                                        }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th
                                                        v-tooltip="
                                                            'Chênh lệch dự kiến khi hoàn thành (VAC) = BAC - EAC.'
                                                        "
                                                    >
                                                        Variance At Completion
                                                        (VAC):
                                                    </th>
                                                    <td>
                                                        {{
                                                            props.projectAudit
                                                                .vac
                                                        }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th
                                                        v-tooltip="
                                                            'Ước tính effort còn lại để hoàn thành dự án (ETC) = EAC - AC.'
                                                        "
                                                    >
                                                        Estimate To Complete
                                                        (ETC):
                                                    </th>
                                                    <td>
                                                        {{
                                                            props.projectAudit
                                                                .etc
                                                        }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th
                                                        v-tooltip="
                                                            'Chỉ số hiệu suất cần đạt để hoàn thành dự án đúng ngân sách (TCPI) = (BAC - EV) / (BAC - AC).'
                                                        "
                                                    >
                                                        To Complete Performance
                                                        Index (TCPI):
                                                    </th>
                                                    <td>
                                                        {{
                                                            props.projectAudit
                                                                .tcpi
                                                        }}
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- Line Chart -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card card-info">
                                        <div class="card-header">
                                            <h3 class="card-title">Performance Metrics Chart</h3>

                                            <div class="card-tools">
                                                <button
                                                    type="button"
                                                    class="btn btn-tool"
                                                    data-card-widget="collapse"
                                                >
                                                    <i class="fas fa-minus"></i>
                                                </button>
                                                <button
                                                    type="button"
                                                    class="btn btn-tool"
                                                    data-card-widget="remove"
                                                >
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="chart">
                                                <canvas
                                                    id="performanceChart"
                                                    style="
                                                        min-height: 250px;
                                                        height: 250px;
                                                        max-height: 250px;
                                                        max-width: 100%;
                                                    "
                                                ></canvas>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Burndown Chart -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title">Burndown Chart</h3>
                                        </div>
                                        <div class="card-body">
                                            <div class="chart">
                                                <canvas
                                                    id="burndownChart"
                                                    style="
                                                        min-height: 250px;
                                                        height: 250px;
                                                        max-height: 250px;
                                                        max-width: 100%;
                                                    "
                                                ></canvas>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <LogWorkModal
            :showModal="showLogWorkModal"
            :task="selectedTask"
            :projectId="projectId"
            @close="showLogWorkModal = false"
            @update-data="handleTaskUpdate"
        />
    </div>
</template>

<script setup>
import { computed, ref, nextTick, onMounted, watch, reactive } from "vue";
import Swal from "sweetalert2";
import LogWorkModal from "../../components/LogWorkModal.vue";
import {
    TASK_ICONS,
    TASK_STATUS,
    TASK_TYPES,
    statusClass,
} from "../../constants/taskConstants";

const props = defineProps({
    projectId: String,
    taskListData: Object,
    blankQuery: Boolean,
    visibleColumns: Array,
    listAssignee: Object,
    listStatuses: Object,
    listPriorities: Object,
    hasPermissionClient: Boolean,
    hasPermissionPm: Boolean,
    hasPermissionStaff: Boolean,
    currentUserId: Number,
    currentUserAccount: String,
    filtersQuery: Object,
    projectAudit: Object,
});

const expandedTasks = ref([]);
const selectedTask = ref(null);
const showLogWorkModal = ref(false);
const globalIsEditting = ref(false);

// Emit sự kiện update để thông báo lên component cha
const emit = defineEmits(["update-data", "task-list-editing"]);

// Kiểm tra xem cột có hiển thị không
const isColumnVisible = (column) => {
    return props.visibleColumns.includes(column);
};

// Hàm bật chế độ edit
const editTask = (task) => {
    emit("task-list-editing", true);
    if (globalIsEditting.value) {
        toastr.error(
            "Other worklog edit is in progress. Please cancel it before edit other."
        );
        return;
    }
    globalIsEditting.value = true;

    task.isEditing = true;
    task.editedName = task.name;
    task.editedPriority = task.priority;
    task.editedAssignee = task.assignee;
    task.editedStatus = task.status;
    task.editedPlanStartDate = task.plan_start_date;
    task.editedPlanEndDate = task.plan_end_date;
    task.editedActualStartDate = task.actual_start_date;
    task.editedActualEndDate = task.actual_end_date;
    task.editedPlanEffort = task.plan_effort;

    nextTick(initPlugins(task));
};

// Hàm khởi tạo Select2 với các sự kiện chung
const initSelect2 = (selector, updateTaskField) => {
    $(selector)
        .select2()
        .on("change", function () {
            updateTaskField($(this).val());
        })
        .on("select2:open", () => {
            setTimeout(
                () =>
                    $(
                        ".select2-container--open .select2-search__field"
                    )[0]?.focus(),
                50
            );
        });
};

// Hàm khởi tạo datetimepicker với sự kiện thay đổi giá trị
const initDatePicker = (selector, updateTaskField) => {
    $(selector).datetimepicker({
        format: "YYYY-MM-DD",
        widgetPositioning: {
            horizontal: "right", // hoặc "left", "right"
            vertical: "bottom", // hoặc "top"
        },
        buttons: { showToday: true, showClear: true, showClose: true },
        icons: {
            today: "fa fa-calendar-day",
            clear: "fa fa-trash",
            close: "fa fa-times",
        },
    });

    $(selector).on("change.datetimepicker", function (e) {
        updateTaskField(
            e.date ? e.date.format("YYYY-MM-DD") : e.target.value || ""
        );
    });
};

// Hàm kích hoạt toàn bộ plugins
const initPlugins = (task) => {
    nextTick(() => {
        // Khởi tạo Select2
        initSelect2(".assignee-select", (val) => (task.editedAssignee = val));
        initSelect2(".priority-select", (val) => (task.editedPriority = val));
        initSelect2(".status-select", (val) => (task.editedStatus = val));

        // Khởi tạo Datepicker
        initDatePicker(
            ".plan-start-datepicker",
            (val) => (task.editedPlanStartDate = val)
        );
        initDatePicker(
            ".plan-end-datepicker",
            (val) => (task.editedPlanEndDate = val)
        );
        initDatePicker(
            ".actual-start-datepicker",
            (val) => (task.editedActualStartDate = val)
        );
        initDatePicker(
            ".actual-end-datepicker",
            (val) => (task.editedActualEndDate = val)
        );
    });
};

const updateTask = async (task) => {
    // Hủy Select2 trước khi cập nhật giao diện
    destroySelect2();

    // Tạo một object mới với dữ liệu đã chỉnh sửa
    const updatedTask = {
        ...task, // Giữ lại các thuộc tính cũ
        name: task.editedName,
        priority: task.editedPriority,
        assignee: task.editedAssignee,
        status: task.editedStatus,
        plan_start_date: task.editedPlanStartDate,
        plan_end_date: task.editedPlanEndDate,
        actual_start_date: task.editedActualStartDate,
        actual_end_date: task.editedActualEndDate,
        plan_effort: task.editedPlanEffort,
        isEditing: false,
    };

    try {
        // Gọi API cập nhật dữ liệu
        const url = props.hasPermissionStaff
            ? `/api/staff/${props.projectId}/tasks/${task.id}/update`
            : `/api/pm/${props.projectId}/tasks/${task.id}/update`;
        await axios.put(url, {
            name: updatedTask.name,
            priority: updatedTask.priority, // Map priority
            assignee: updatedTask.assignee,
            status: updatedTask.status, // Map status
            plan_start_date: updatedTask.plan_start_date,
            plan_end_date: updatedTask.plan_end_date,
            actual_start_date: updatedTask.actual_start_date,
            actual_end_date: updatedTask.actual_end_date,
            ...(updatedTask.plan_effort !== null && {
                plan_effort: updatedTask.plan_effort,
            }),
        });

        toastr.success("Updated successfully!");
    } catch (error) {
        toastr.error(
            error.response?.data?.error || "❌ Có lỗi xảy ra khi gửi dữ liệu."
        );
    } finally {
        task.isEditing = false;
        globalIsEditting.value = false;
        emit("task-list-editing", false);
        // Emit để component cha xử lý
        emit("update-data");
    }
};

const cancelEdit = (task) => {
    // Hủy Select2 trước khi cập nhật DOM
    destroySelect2();

    Object.assign(task, task.originalData); // Khôi phục dữ liệu gốc
    task.isEditing = false;
    globalIsEditting.value = false;
    emit("task-list-editing", false);
};

const copyTaskLink = (task) => {
    const taskLink = `${window.location.origin}/${props.projectId}/task/${task.id}`;
    navigator.clipboard
        .writeText(taskLink)
        .then(() => {
            toastr.success("Link copied to clipboard!");
        })
        .catch(() => {
            toastr.error("Failed to copy link.");
        });
};

const destroySelect2 = () => {
    $(".assignee-select").select2("destroy");
    $(".priority-select").select2("destroy");
    $(".status-select").select2("destroy");
};

const confirmDelete = async (task) => {
    let warningMessage = "Bạn có chắc chắn muốn xoá task này?";

    if (task.type === TASK_TYPES.EPIC) {
        warningMessage =
            "⚠️ Task này là một Epic! Nếu bạn xoá nó, tất cả task con cũng sẽ bị xoá. Bạn có chắc chắn muốn tiếp tục?";
    }

    const result = await Swal.fire({
        title: warningMessage,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "OK",
        cancelButtonText: "Hủy",
    });

    if (result.isConfirmed) {
        softDelete(task.id);
    }
};

const softDelete = async (taskId) => {
    try {
        const url = `/api/pm/${props.projectId}/tasks/${taskId}/destroy`; // API xoá mềm
        await axios.delete(url);
        toastr.success("Task deleted successfully!");
        // Emit để component cha xử lý
        emit("update-data");
    } catch (error) {
        // Lấy thông tin lỗi từ response
        const errorMessage =
            error.response?.data?.message || "Failed to create task!";
        const errorDetail = error.response?.data?.error || "Unknown error";

        // Hiển thị toastr lỗi với cả message và error detail
        toastr.error(`${errorMessage}: ${errorDetail}`);
    }
};

const openLogWorkModal = (task) => {
    selectedTask.value = task;
    showLogWorkModal.value = true;
};

const handleTaskUpdate = () => {
    emit("update-data");
};

import tippy from "tippy.js";
// Directive tùy chỉnh để dùng Tippy.js
const vTooltip = {
    mounted(el, binding) {
        tippy(el, {
            content: binding.value,
            placement: "top",
            animation: "scale",
            theme: "light-border",
        });
    },
};

const toggleTask = (taskId) => {
    if (expandedTasks.value.includes(taskId)) {
        expandedTasks.value = expandedTasks.value.filter((id) => id !== taskId);
    } else {
        expandedTasks.value.push(taskId);
    }
};

const isExpanded = computed(() => (taskId) => {
    return expandedTasks.value.includes(Number(taskId));
});

const labels = ref([]);
const cpiSeries = ref([]);
const spiSeries = ref([]);
let chartInstance = null;

const projecWorklogs = ref([]);

import { format, subDays, isWeekend } from "date-fns";

const usersWithoutWorklog = ref([]);
const lastWorkday = ref("");

const getLastWorkday = () => {
    let date = subDays(new Date(), 1);
    while (isWeekend(date)) {
        date = subDays(date, 1);
    }
    return format(date, "yyyy-MM-dd");
};

const projectWorklogIsLoading = ref(false);

onMounted(async () => {
    projectWorklogIsLoading.value = true;
    globalIsEditting.value = false;

    const response = await axios.get(`/api/project-metrics/${props.projectId}`);

    labels.value = response.data.labels;
    cpiSeries.value = response.data.cpi_series;
    spiSeries.value = response.data.spi_series;

    renderChart();

    const worklogResponse = await axios.get(
        `/api/project/${props.projectId}/worklog/`
    );
    projecWorklogs.value = worklogResponse.data.original.data;

    const worklogAuditResponse = await axios.get(
        `/api/project/${props.projectId}/users-without-worklogs`
    );
    usersWithoutWorklog.value = worklogAuditResponse.data.original.data || [];
    lastWorkday.value = getLastWorkday();
    projectWorklogIsLoading.value = false;
});

// Computed để nhóm user theo ngày
const groupedUsers = computed(() => {
    if (!Array.isArray(usersWithoutWorklog.value)) return {}; // Kiểm tra mảng hợp lệ

    let grouped = {};

    for (const user of usersWithoutWorklog.value) {
        if (Array.isArray(user.missing_days)) {
            // Kiểm tra missing_days có phải là mảng không
            for (const date of user.missing_days) {
                if (!grouped[date]) {
                    grouped[date] = [];
                }
                grouped[date].push(user.user_name);
            }
        }
    }

    return Object.fromEntries(
        Object.entries(grouped).sort((a, b) => b[0].localeCompare(a[0]))
    );
});

function renderChart() {
    const ctx = document.getElementById("performanceChart").getContext("2d");

    if (chartInstance) {
        chartInstance.destroy(); // Xóa chart cũ nếu có
    }

    const chartData = {
        labels: labels.value,
        datasets: [
            {
                label: "CPI",
                backgroundColor: "rgba(60,141,188,0.9)",
                borderColor: "rgba(60,141,188,0.8)",
                pointRadius: false,
                pointColor: "#3b8bba",
                pointStrokeColor: "rgba(60,141,188,1)",
                pointHighlightFill: "#fff",
                pointHighlightStroke: "rgba(60,141,188,1)",
                fill: false,
                data: cpiSeries.value,
            },
            {
                label: "SPI",
                backgroundColor: "rgba(210, 214, 222, 1)",
                borderColor: "rgba(210, 214, 222, 1)",
                pointRadius: false,
                pointColor: "rgba(210, 214, 222, 1)",
                pointStrokeColor: "#c1c7d1",
                pointHighlightFill: "#fff",
                pointHighlightStroke: "rgba(220,220,220,1)",
                fill: false,
                data: spiSeries.value,
            },
        ],
    };

    const chartOptions = {
        maintainAspectRatio: false,
        responsive: true,
        legend: {
            display: true,
        },
        scales: {
            x: {
                grid: { display: false },
            },
            y: {
                grid: { display: false },
            },
        },
    };

    chartInstance = new Chart(ctx, {
        type: "line",
        data: chartData,
        options: chartOptions,
    });
}

// Add burndown chart data and initialization
const burndownChart = ref(null);

const calculateBurndownData = () => {
    if (!props.taskListData?.tasks) return null;

    const tasks = props.taskListData.tasks;
    const totalEffort = tasks.reduce((sum, task) => sum + (Number(task.plan_effort) || 0), 0);
    
    // Calculate ideal burndown line
    const startDate = new Date(Math.min(...tasks
        .filter(task => task.plan_start_date)
        .map(task => new Date(task.plan_start_date))));
    const endDate = new Date(Math.max(...tasks
        .filter(task => task.plan_end_date)
        .map(task => new Date(task.plan_end_date))));
    const days = Math.ceil((endDate - startDate) / (1000 * 60 * 60 * 24)) + 1;

    const idealBurndown = [];
    const actualBurndown = [];
    const labels = [];

    for (let i = 0; i < days; i++) {
        const currentDate = new Date(startDate);
        currentDate.setDate(startDate.getDate() + i);
        labels.push(currentDate.toLocaleDateString());

        // Ideal burndown
        const idealRemaining = totalEffort * (1 - i / days);
        idealBurndown.push(idealRemaining);

        // Actual burndown
        const actualRemaining = tasks.reduce((sum, task) => {
            const taskDate = new Date(task.plan_start_date);
            if (taskDate <= currentDate) {
                if (task.status === TASK_STATUS.DONE) {
                    return sum;
                }
                return sum + (Number(task.plan_effort) || 0);
            }
            return sum;
        }, totalEffort);
        actualBurndown.push(actualRemaining);
    }

    return {
        labels,
        idealBurndown,
        actualBurndown,
        totalEffort
    };
};

const initBurndownChart = () => {
    const ctx = document.getElementById('burndownChart');
    if (!ctx) return;

    const data = calculateBurndownData();
    if (!data) return;

    if (burndownChart.value) {
        burndownChart.value.destroy();
    }

    burndownChart.value = new Chart(ctx, {
        type: 'line',
        data: {
            labels: data.labels,
            datasets: [
                {
                    label: 'Ideal Burndown',
                    data: data.idealBurndown,
                    borderColor: 'rgb(75, 192, 192)',
                    backgroundColor: 'rgba(75, 192, 192, 0.1)',
                    tension: 0.1,
                    fill: true
                },
                {
                    label: 'Actual Burndown',
                    data: data.actualBurndown,
                    borderColor: 'rgb(255, 99, 132)',
                    backgroundColor: 'rgba(255, 99, 132, 0.1)',
                    tension: 0.1,
                    fill: true
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Remaining Effort (hours)'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Date'
                    }
                }
            },
            plugins: {
                title: {
                    display: true,
                    text: 'Project Burndown Chart'
                },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                    callbacks: {
                        label: function(context) {
                            return `${context.dataset.label}: ${context.parsed.y.toFixed(2)} hours`;
                        }
                    }
                }
            }
        }
    });
};

// Watch for changes in taskListData to update the burndown chart
watch(() => props.taskListData, () => {
    nextTick(() => {
        initBurndownChart();
    });
}, { deep: true });

// Initialize burndown chart when component is mounted
onMounted(() => {
    nextTick(() => {
        initBurndownChart();
    });
});
</script>

<style>
.no-spinner {
    appearance: textfield;
}

.no-spinner::-webkit-inner-spin-button,
.no-spinner::-webkit-outer-spin-button {
    -webkit-appearance: none;
    margin: 0;
}
</style>

<style scoped>
table tbody tr {
    height: 12px !important;
}

table tbody tr td {
    padding: 1px 1px !important;
    vertical-align: middle;
}

table tbody tr:hover {
    background-color: #b3e0f5 !important;
    transition: background-color 0.2s ease-in-out !important;
}

.nav-pills .nav-link.active {
    background-color: #263d7c !important;
    /* Màu xanh lá đậm hơn */
    border-color: #1e407e !important;
    color: white !important;
    /* Chữ trắng rõ hơn */
}

.nav-pills .nav-link:hover {
    background-color: #0d36a5 !important;
    /* Màu tối hơn khi hover */
    color: white !important;
}

.overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    color: rgb(0, 0, 0);
    font-weight: bold;
    z-index: 10;
    border-radius: 8px;
}

.spinner {
    width: 40px;
    height: 40px;
    border: 5px solid rgba(255, 255, 255, 0.3);
    border-top-color: blue;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin-right: 10px;
}

@keyframes spin {
    0% {
        transform: rotate(0deg);
    }

    100% {
        transform: rotate(360deg);
    }
}

.triangle-right,
.triangle-down {
    margin-left: 5px;
    display: inline-block;
    width: 0;
    height: 0;
    border-style: solid;
    cursor: pointer;
    transition: transform 0.2s ease-in-out, border-color 0.2s ease-in-out;
}

.triangle-right {
    border-width: 6px 0 6px 10px;
    border-color: transparent transparent transparent #3498db; /* Màu xanh dương */
}

.triangle-down {
    border-width: 10px 6px 0 6px;
    border-color: #e74c3c transparent transparent transparent; /* Màu đỏ */
}

/* Hover: Phóng to + đổi màu */
.triangle-right:hover {
    transform: scale(1.3);
    border-color: transparent transparent transparent #1abc9c; /* Màu xanh ngọc */
}

.triangle-down:hover {
    transform: scale(1.3);
    border-color: #f39c12 transparent transparent transparent; /* Màu cam */
}
</style>
