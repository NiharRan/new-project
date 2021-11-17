<template>
  <el-main class="bg-white shadow" v-loading="loading">
    <el-button type="primary" icon="el-icon-plus" @click="handleModal(true)"
      >New</el-button
    >
    <div
      style="display: flex; align-items: center; gap: 20px; margin-top: 20px"
    >
      <div style="width: 200px">
        <el-select
          v-model="field"
          placeholder="Select field"
          value-key="id"
          @change="filterData"
        >
          <el-option
            v-for="item in new_fields"
            :key="item.name"
            :label="item.label"
            :value="item"
          >
          </el-option>
        </el-select>
      </div>
      <div style="flex: 1">
        <el-tag
          v-for="(field, index) in selected_fields"
          :key="index"
          closable
          @close="removeField(index, field.name)"
          type="success"
        >
          {{ field.label }}
        </el-tag>
      </div>
    </div>
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
      <el-table-column label="Email" v-if="isExist('email')" prop="email">
      </el-table-column>
      <el-table-column label="Phone" v-if="isExist('phone')" prop="phone">
      </el-table-column>
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
import { defaultFormData, default_fields, new_fields } from "./helper";

export default {
  components: { AddEditDialog },
  name: "Project",
  setup() {
    const $rest = inject("$rest");
    let formData = reactive(defaultFormData());
    let errors = ref({});
    let visible = ref(false);
    let editable = ref(false);
    let search = ref("");
    let loading = ref(false);
    let users = ref(null);
    let selected_fields = ref([]);
    let field = ref({});

    const { fetchData } = useFetch(loading);
    const fetchUsers = async function () {
      let d_fields = [];
      if (default_fields) {
        d_fields = await default_fields.map((row) => row.name);
      }
      let s_fields = [];
      if (selected_fields.value.length > 0) {
        s_fields = await selected_fields.value.map((row) => row.name);
      }
      const data = await fetchData("users", {
        default_fields: d_fields.toString(),
        fields: s_fields.toString(),
      });
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
          handleEdit(defaultFormData());
          ElNotification(notify("Users", response.message, "success"));
          handleModal(false);
        }
      } catch (error) {
        handleErrors(error);
        ElNotification(notify("Oops", error.statusText, "error"));
      }
    };

    const filterData = function () {
      if (field.value != {}) {
        selected_fields.value.push(field.value);
        fetchUsers();
        isExist(field.value.name);
        field.value = {};
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

    const isExist = function (value) {
      const result = selected_fields.value.find(function (row) {
        return row.name === value;
      });
      return result ? true : false;
    };

    const removeField = function (index, value) {
      selected_fields.value.splice(index, 1);
      isExist(value);
    };

    const clearData = function () {
      errors.value = {};
      handleEdit(defaultFormData());
      handleModal(false);
    };

    return {
      visible,
      editable,
      formData,
      selected_fields,
      default_fields,
      new_fields,
      field,
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
      filterData,
      isExist,
      removeField,
    };
  },
};
</script>
