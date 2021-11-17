<template>
  <el-main class="bg-white shadow" v-loading="loading">
    <div style="display: flex; justify-content: space-between">
      <el-button type="info" icon="el-icon-d-arrow-left" @click="back"
        >Back</el-button
      >
      <el-button type="primary" icon="el-icon-plus" @click="handleModal(true)"
        >New</el-button
      >
    </div>

    <div>
      <h1 class="project-title">{{ details?.name }}</h1>
    </div>

    <el-table
      v-if="details?.tasks?.data?.length > 0"
      :data="details.tasks.data"
      style="width: 100%; margin-top: 20px"
    >
      <el-table-column label="Date" prop="created_at">
        <template #default="scope">
          {{ longLocalDate(scope.row.created_at) }}
        </template>
      </el-table-column>
      <el-table-column label="Name" prop="name"> </el-table-column>
      <el-table-column label="Assigned To">
        <template #default="scope">
          <div style="display: flex; gap: 15px">
            <router-link class="user-tag" :to="scope.row.to.link">{{
              scope.row.to.full_name
            }}</router-link>
          </div>
        </template>
      </el-table-column>
      <el-table-column label="Assigned By">
        <template #default="scope">
          <div style="display: flex; gap: 15px">
            <router-link class="user-tag" :to="scope.row.by.link">{{
              scope.row.by.full_name
            }}</router-link>
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
      :hideColumns="{ project: true }"
      :form="formData"
      :users="users"
      :errors="errors"
      @close="clearData"
      @save="handleSubmit"
    />
  </el-main>
</template>

<script>
import { useRoute, useRouter } from "vue-router";
import { ref, reactive } from "vue";
import { useNotification, useDateTime } from "@/admin/Bits/Composables";
import { useModal, useProject, useUser } from "../../composables";
import AddEditDialog from "../Task/AddEditDialog.vue";
import { ElNotification } from "element-plus";
import Helper from "../Task/helper";

export default {
  components: { AddEditDialog },
  setup() {
    const route = useRoute();
    const router = useRouter();
    const slug = route.params.slug;

    let formData = reactive(Helper.defaultFormData());
    let errors = reactive({ name: "" });
    let visible = ref(false);
    let editable = ref(false);

    let details = ref(null);
    let loading = ref(false);
    let users = ref(null);

    const { fetchProjectDetails } = useProject(loading);
    const { fetchUsers } = useUser(loading);
    fetchProjectDetails(`projects/${slug}`, details);
    fetchUsers("users", users, { dropdown: true });

    const { longLocalDate } = useDateTime();
    const { handleModal } = useModal(visible, editable);
    const { notify } = useNotification();

    const getUser = function () {
      return typeof formData.user === "object" && formData.user != null
        ? formData.user.id
        : formData.user;
    };

    const handleSubmit = async function () {
      const url = editable.value == true ? `tasks/${formData.id}` : "tasks";
      let response = null;
      let user = getUser();
      formData.user = user;
      formData.project = details.value.id;
      try {
        if (editable.value == true) {
          response = await $rest.put(url, formData);
        } else {
          response = await $rest.post(url, formData);
        }
        if (response) {
          fetchProjectDetails(`projects/${slug}`, details);
          handleEdit(Helper.defaultFormData(details.value.id));
          handleModal(false);
          ElNotification(notify("Tasks", response.message, "success"));
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
      formData.user = row.to;
      formData.name = row.name;
      formData.status = row.status;
      visible.value = true;
    };
    const handleDelete = async function (row) {
      try {
        const response = await $rest.delete(`tasks/${row.id}`);
        if (response) {
          fetchTasks();
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
      details,
      back,
      formData,
      users,
      errors,
      visible,
      editable,
      handleDelete,
      clearData,
      handleEdit,
      handleSubmit,
      longLocalDate,
      handleModal,
      fetchProjectDetails,
    };
  },
};
</script>
