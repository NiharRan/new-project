<template>
  <el-main class="bg-white shadow" v-loading="loading">
    <el-button type="primary" icon="el-icon-plus" @click="handleModal(true)"
      >New</el-button
    >

    <el-table
      v-if="projects && projects.data.length > 0"
      :data="projects.data"
      style="width: 100%; margin-top: 20px"
    >
      <el-table-column label="Date" prop="created_at">
        <template #default="scope">
          {{ formatDate(scope.row.created_at) }}
        </template>
      </el-table-column>
      <el-table-column label="Name">
        <template #default="scope">
          <router-link :to="scope.row.link">{{ scope.row.name }}</router-link>
        </template>
      </el-table-column>
      <el-table-column label="Tasks" prop="total_tasks"> </el-table-column>
      <el-table-column label="Assigned To">
        <template #default="scope">
          <div style="display: flex; gap: 15px">
            <router-link
              class="user-tag"
              v-for="(user, key) in scope.row.users"
              :key="key"
              :to="user.link"
              >{{ user.full_name }}</router-link
            >
          </div>
        </template>
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
      :form="formData"
      :errors="errors"
      :users="users"
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
  useUser,
  useDateTime,
  useNotification,
} from "../../composables";
import AddEditDialog from "./AddEditDialog.vue";
import { ElNotification } from "element-plus";
import Rest from "@/admin/Bits/Rest";
import Helper from "./helper";

export default {
  components: { AddEditDialog },
  name: "Project",
  setup() {
    let formData = reactive(Helper.defaultFormData());
    let errors = reactive({ name: "" });
    let visible = ref(false);
    let editable = ref(false);
    let search = ref("");
    let loading = ref(false);
    let projects = ref(null);
    let users = ref(null);

    const { fetchProjects } = useProject(loading);
    const { fetchUsers } = useUser(loading);
    fetchProjects("projects", projects);
    fetchUsers("users", users, { dropdown: true });

    const { formatDate } = useDateTime();
    const { handleModal } = useModal(visible, editable);
    const { notify } = useNotification();

    const getUsers = function () {
      return formData.users.map((row) => row.id);
    };

    const handleSubmit = async function () {
      const url =
        editable.value == true ? `projects/${formData.id}` : "projects";
      let response = null;
      let users = getUsers();
      formData.users = users;
      try {
        if (editable.value == true) {
          response = await Rest.put(url, formData);
        } else {
          response = await Rest.post(url, formData);
        }
        if (response) {
          fetchProjects("projects", projects);
          handleEdit(Helper.defaultFormData());
          ElNotification(notify("Projects", response.message, "success"));
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
      formData.name = row.name;
      formData.users = row.users;
      formData.status = row.status;
      visible.value = true;
    };
    const handleDelete = async function (row) {
      try {
        const response = await Rest.delete(`projects/${row.id}`);
        if (response) {
          fetchProjects("projects", projects);
          ElNotification(notify("Projects", response.message, "success"));
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
      users,
      loading,
      search,
      handleEdit,
      handleDelete,
      handleSubmit,
      handleModal,
      formatDate,
      notify,
      clearData,
      getUsers,
    };
  },
};
</script>

