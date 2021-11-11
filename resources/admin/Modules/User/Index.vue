<template>
  <el-main class="bg-white shadow" v-loading="loading">
    <el-button type="primary" icon="el-icon-plus" @click="handleModal(true)"
      >New</el-button
    >

    <el-table
      v-if="users && users.data.length > 0"
      :data="users.data"
      style="width: 100%; margin-top: 20px"
    >
      <el-table-column label="Date" prop="created_at">
        <template #default="scope">
          {{ longLocalDate(scope.row.created_at) }}
        </template>
      </el-table-column>
      <el-table-column label="Name">
        <template #default="scope">
          <router-link :to="scope.row.link">{{
            scope.row.full_name
          }}</router-link>
        </template>
      </el-table-column>
      <el-table-column label="Email" prop="email"> </el-table-column>
      <el-table-column label="Phone" prop="phone"> </el-table-column>
      <el-table-column label="Projects" prop="total_projects">
      </el-table-column>
      <el-table-column label="Assigned Tasks" prop="total_assigned_tasks">
      </el-table-column>
      <el-table-column label="Assigned By" prop="total_assigned_by_tasks">
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
      @close="clearData"
      @save="handleSubmit"
    />
  </el-main>
</template>

<script>
import { ref, reactive, inject } from "vue";
import { useModal, useFetch, useErrors } from "../../composables";
import { useNotification, useDateTime } from "@/admin/Bits/Composables";
import AddEditDialog from "./AddEditDialog.vue";
import { ElNotification } from "element-plus";
import Helper from "./helper";

export default {
  components: { AddEditDialog },
  name: "Project",
  setup() {
    const $rest = inject("$rest");
    let formData = reactive(Helper.defaultFormData());
    let errors = ref({});
    let visible = ref(false);
    let editable = ref(false);
    let search = ref("");
    let loading = ref(false);
    let users = ref(null);

    const { fetchData } = useFetch(loading);
    const fetchUsers = async function () {
      const data = await fetchData("users");
      if (data) {
        users.value = data.users;
      }
    };
    fetchUsers();

    const { longLocalDate } = useDateTime();
    const { handleModal } = useModal(visible, editable);
    const { notify } = useNotification();
    const { handleErrors } = useErrors(errors);

    const handleSubmit = async function () {
      const url = editable.value == true ? `users/${formData.id}` : "users";
      let response = null;
      try {
        if (editable.value == true) {
          response = await $rest.put(url, formData);
        } else {
          response = await $rest.post(url, formData);
        }
        if (response) {
          fetchUsers();
          handleEdit(Helper.defaultFormData());
          ElNotification(notify("Users", response.message, "success"));
          handleModal(false);
        }
      } catch (error) {
        handleErrors(error);
        ElNotification(notify("Oops", error.statusText, "error"));
      }
    };

    const handleEdit = function (row) {
      editable.value = true;
      formData.id = row.id;
      formData.first_name = row.first_name;
      formData.last_name = row.last_name;
      formData.email = row.email;
      formData.phone = row.phone;
      formData.status = row.status;
      visible.value = true;
    };
    const handleDelete = async function (row) {
      try {
        const response = await $rest.delete(`users/${row.id}`);
        if (response) {
          fetchUsers();
          ElNotification(notify("Users", response.message, "success"));
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
      users,
      loading,
      search,
      fetchData,
      handleEdit,
      handleDelete,
      handleSubmit,
      handleModal,
      longLocalDate,
      notify,
      clearData,
    };
  },
};
</script>
