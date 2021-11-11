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
      <h1 class="project-title">{{ details?.full_name }}</h1>
    </div>

    <el-table
      v-if="details && details.projects && details.projects.length > 0"
      :data="details.projects"
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
      <el-table-column label="Tasks" prop="total_tasks"> </el-table-column>
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
import { useRoute, useRouter } from "vue-router";
import { ref, reactive } from "vue";
import { useModal, useFetch } from "../../composables";
import { useNotification, useDateTime } from "@/admin/Bits/Composables";
import AddEditDialog from "../Project/AddEditDialog.vue";
import { ElNotification } from "element-plus";
import Helper from "../Project/helper";
import Rest from "@/admin/Bits/Rest";

export default {
  components: { AddEditDialog },
  setup() {
    const route = useRoute();
    const router = useRouter();
    const slug = route.params.slug;

    let formData = reactive(Helper.defaultFormData(slug));
    let errors = reactive({ name: "" });
    let visible = ref(false);
    let editable = ref(false);

    let details = ref(null);
    let loading = ref(false);

    const { fetchData } = useFetch(loading);
    const fetchUserDetails = async function () {
      const data = await fetchData(`users/${slug}`);
      if (data) {
        details.value = data.user;
      }
    };
    fetchUserDetails();

    const back = function () {
      router.push("/users");
    };

    const { longLocalDate } = useDateTime();
    const { handleModal } = useModal(visible, editable);
    const { notify } = useNotification();

    const handleSubmit = async function () {
      const url =
        editable.value == true ? `projects/${formData.id}` : "projects";
      let response = null;
      try {
        if (editable.value == true) {
          response = await Rest.put(url, formData);
        } else {
          response = await Rest.post(url, formData);
        }
        if (response) {
          fetchUserDetails();
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
      formData.project = details.id;
      formData.name = row.name;
      formData.status = row.status;
      visible.value = true;
    };
    const handleDelete = async function (row) {
      try {
        const response = await Rest.delete(`projects/${row.id}`);
        if (response) {
          fetchProjects();
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
      details,
      fetchData,
      back,
      formData,
      errors,
      visible,
      editable,
      handleDelete,
      clearData,
      handleEdit,
      handleSubmit,
      longLocalDate,
      handleModal,
    };
  },
};
</script>
