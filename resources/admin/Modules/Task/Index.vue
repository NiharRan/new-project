<template>
  <el-main class="bg-white" v-loading="loading">
    <el-button type="primary" icon="el-icon-plus" @click="handleModal(true)"
      >New</el-button
    >

    <el-table
      v-if="tasks && tasks.data.length > 0"
      :data="tasks.data"
      style="width: 100%; margin-top: 20px"
    >
      <el-table-column label="Date" prop="created_at">
        <template #default="scope">
          {{ longLocalDate(scope.row.created_at) }}
        </template>
      </el-table-column>
      <el-table-column label="Name">
        <template #default="scope">
          <router-link :to="scope.row.link">{{ scope.row.name }}</router-link>
        </template>
      </el-table-column>
      <el-table-column label="Project" #default="scope">
        <router-link
          v-if="scope.row.project"
          style="color: blue"
          :to="scope.row.project.link"
          >{{ scope.row.project.name }}</router-link
        >
      </el-table-column>
      <el-table-column label="Assign To" #default="scope">
        <router-link
          v-if="scope.row.to"
          style="color: blue"
          :to="scope.row.to.link"
          >{{ scope.row.to.full_name }}</router-link
        >
      </el-table-column>
      <el-table-column align="right">
        <template #default="scope">
          <el-button size="mini" @click="handleEdit(scope.row)">Edit</el-button>
          <el-popconfirm
            @confirm="handleDelete(scope.row)"
            title="Are you sure to delete this?"
          >
            <template #reference>
              <el-button size="mini" type="danger">Delete</el-button>
            </template>
          </el-popconfirm>
        </template>
      </el-table-column>
    </el-table>
    <add-edit-dialog
      :editable="editable"
      :visible="visible"
      :projects="projects"
      :users="users"
      :form="formData"
      :errors="errors"
      :hideColumns="{}"
      @close="clearData"
      @save="handleSubmit"
      @fetchUsers="fetchUsers"
    />
  </el-main>
</template>

<script>
import { ref, reactive, inject } from "vue";
import { useModal, useProject, useTask, useErrors } from "../../composables";
import { useNotification, useDateTime } from "@/admin/Bits/Composables";
import AddEditDialog from "./AddEditDialog.vue";
import { ElNotification } from "element-plus";
import Helper from "./helper";

export default {
  components: { AddEditDialog },
  name: "Task",
  setup() {
    const $rest = inject("$rest");
    let formData = reactive(Helper.defaultFormData());
    let errors = ref({});
    let visible = ref(false);
    let editable = ref(false);
    let search = ref("");
    let loading = ref(false);
    let tasks = ref(null);
    let projects = ref(null);
    let users = ref(null);

    const { fetchTasks } = useTask(loading);
    const { fetchProjects, fetchProjectUsers } = useProject(loading);
    fetchTasks("tasks", tasks);
    fetchProjects("projects", projects, { dropdown: true });
    const fetchUsers = function () {
      let project = getProject();
      let url = `projects/users/${project}`;
      fetchProjectUsers(url, users);
    };

    const { longLocalDate } = useDateTime();
    const { handleModal } = useModal(visible, editable);
    const { notify } = useNotification();
    const { handleErrors } = useErrors(errors);

    const getProject = function () {
      return typeof formData.project === "object" && formData.project != null
        ? formData.project.id
        : formData.project;
    };

    const getUser = function () {
      return typeof formData.user === "object" && formData.project != null
        ? formData.user.id
        : formData.user;
    };

    const handleSubmit = async function () {
      const url = editable.value == true ? `tasks/${formData.id}` : "tasks";
      let response = null;
      let project = getProject();
      let user = getUser();

      formData.project = project;
      formData.user = user;
      try {
        if (editable.value == true) {
          response = await $rest.put(url, formData);
        } else {
          response = await $rest.post(url, formData);
        }
        if (response) {
          handleModal(false);
          fetchTasks("tasks", tasks);
          handleEdit(Helper.defaultFormData());
          ElNotification(notify("Tasks", response.message, "success"));
        }
      } catch (error) {
        handleErrors(error);
        ElNotification(notify("Oops", error.statusText, "error"));
      }
    };

    const handleEdit = function (row) {
      editable.value = true;
      formData.id = row.id;
      formData.project = row.project;
      formData.user = row.to;
      formData.name = row.name;
      formData.status = row.status;
      visible.value = true;
      if (formData.project) {
        fetchUsers();
      }
    };
    const handleDelete = async function (row) {
      try {
        const response = await $rest.delete(`tasks/${row.id}`);
        if (response) {
          fetchTasks("tasks", tasks);
          ElNotification(notify("Tasks", response.message, "success"));
        }
      } catch (error) {
        ElNotification(notify("Oops", error.statusText, "error"));
      }
    };

    const clearData = function () {
      errors.value = {};
      handleEdit(Helper.defaultFormData());
      handleModal(false);
    };

    return {
      visible,
      editable,
      formData,
      errors,
      projects,
      users,
      tasks,
      loading,
      search,
      handleEdit,
      handleDelete,
      handleSubmit,
      handleModal,
      longLocalDate,
      notify,
      clearData,
      fetchProjectUsers,
      fetchUsers,
    };
  },
};
</script>
