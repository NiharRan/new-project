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
          {{ formatDate(scope.row.created_at) }}
        </template>
      </el-table-column>
      <el-table-column label="Name" prop="name"> </el-table-column>
      <el-table-column label="Project" #default="scope">
        <router-link style="color: blue" :to="scope.row.project.link">{{
          scope.row.project.name
        }}</router-link>
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
      :form="formData"
      :errors="errors"
      :hideColumns="{}"
      @close="clearData"
      @save="handleSubmit"
    />
  </el-main>
</template>

<script>
import { ref, reactive } from "vue";
import {
  useModal,
  useProject,
  useTask,
  useDateTime,
  useNotification,
} from "../../composables";
import AddEditDialog from "./AddEditDialog.vue";
import { ElNotification } from "element-plus";
import Rest from "@/admin/Bits/Rest";
import Helper from "./helper";

export default {
  components: { AddEditDialog },
  name: "Task",
  setup() {
    let formData = reactive(Helper.defaultFormData());
    let errors = reactive({ name: "" });
    let visible = ref(false);
    let editable = ref(false);
    let search = ref("");
    let loading = ref(false);
    let tasks = ref(null);
    let projects = ref(null);

    const { fetchTasks } = useTask(loading);
    const { fetchProjects } = useProject(loading);
    fetchTasks("tasks", tasks);
    fetchProjects("projects", projects, { dropdown: true });

    const { formatDate } = useDateTime();
    const { handleModal } = useModal(visible, editable);
    const { notify } = useNotification();

    const handleSubmit = async function () {
      const url = editable.value == true ? `tasks/${formData.id}` : "tasks";
      let response = null;
      try {
        if (editable.value == true) {
          response = await Rest.put(url, formData);
        } else {
          response = await Rest.post(url, formData);
        }
        if (response) {
          fetchTasks('tasks', tasks);
          handleEdit(Helper.defaultFormData());
          ElNotification(notify("Tasks", response.message, "success"));
          handleModal(false);
        }
      } catch (error) {
        errors = error.responseJSON;
        ElNotification(notify("Oops", error.statusText, "error"));
      }
    };

    const handleEdit = function (row) {
      editable.value = true;
      formData.id = row.id;
      formData.project = row.project;
      formData.name = row.name;
      formData.status = row.status;
      visible.value = true;
    };
    const handleDelete = async function (row) {
      try {
        const response = await Rest.delete(`tasks/${row.id}`);
        if (response) {
          fetchTasks('tasks', tasks);
          ElNotification(notify("Tasks", response.message, "success"));
        }
      } catch (error) {
        ElNotification(notify("Oops", error.statusText, "error"));
      }
    };

    const clearData = function () {
      handleEdit(Helper.defaultFormData());
      handleModal(false);
    };

    return {
      visible,
      editable,
      formData,
      errors,
      projects,
      tasks,
      loading,
      search,
      handleEdit,
      handleDelete,
      handleSubmit,
      handleModal,
      formatDate,
      notify,
      clearData,
    };
  },
};
</script>
