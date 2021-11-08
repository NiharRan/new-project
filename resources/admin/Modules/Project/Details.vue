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
          {{ formatDate(scope.row.created_at) }}
        </template>
      </el-table-column>
      <el-table-column label="Name" prop="name"> </el-table-column>
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
      :errors="errors"
      @close="clearData"
      @save="handleSubmit"
    />
  </el-main>
</template>

<script>
import { useRoute, useRouter } from "vue-router";
import { ref, reactive } from "vue";
import {
  useModal,
  useProject,
  useDateTime,
  useNotification,
} from "../../composables";
import AddEditDialog from "../Task/AddEditDialog.vue";
import { ElNotification } from "element-plus";
import Helper from "../Task/helper";
import Rest from "@/admin/Bits/Rest";

export default {
  components: { AddEditDialog },
  setup() {
    const route = useRoute();
    const router = useRouter();
    const id = route.params.id;

    let formData = reactive(Helper.defaultFormData(id));
    let errors = reactive({ name: "" });
    let visible = ref(false);
    let editable = ref(false);

    let details = ref(null);
    let loading = ref(false);

    const { fetchProjectDetails } = useProject(loading);
    fetchProjectDetails(`projects/${id}`, details);

    const back = function () {
      router.push("/projects");
    };

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
          fetchProjectDetails();
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
      formData.project = id;
      formData.name = row.name;
      formData.status = row.status;
      visible.value = true;
    };
    const handleDelete = async function (row) {
      try {
        const response = await Rest.delete(`tasks/${row.id}`);
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
      errors,
      visible,
      editable,
      handleDelete,
      clearData,
      handleEdit,
      handleSubmit,
      formatDate,
      handleModal,
      fetchProjectDetails,
    };
  },
};
</script>
