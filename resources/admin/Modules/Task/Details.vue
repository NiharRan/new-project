<template>
  <el-main class="bg-white shadow" v-loading="loading">
    <div style="display: flex; justify-content: space-between">
      <el-button
        type="info"
        icon="el-icon-d-arrow-left"
        @click="back('/projects')"
        >Back</el-button
      >
    </div>

    <div v-if="details">
      <h1 class="project-title">{{ details?.name }}</h1>
      <p class="task-details">
        <span>
          <span>
            <strong>Project</strong>
            <span
              >:
              <router-link :to="details.project.link">
                {{ details?.project?.name }}
              </router-link>
            </span>
          </span>
          <span> || </span>
          <span>
            <strong>Assigned To</strong>
            <span
              >:
              <router-link :to="details.to.link">
                {{ details?.to?.full_name }}
              </router-link>
            </span>
          </span>
          <span> || </span>
          <span>
            <strong>Assigned By</strong>
            <span
              >:
              <router-link :to="details.by.link">
                {{ details?.by?.full_name }}
              </router-link>
            </span>
          </span>
        </span>
        <span class="time">
          {{ handleTimeAgo(details.created_at) }}
        </span>
      </p>
    </div>
  </el-main>
</template>

<script>
import { useRoute } from "vue-router";
import { ref, reactive } from "vue";
import {
  useNotification,
  useDateTime,
  useApplication,
} from "@/admin/Bits/Composables";
import { useTask } from "../../composables";
import { ElNotification } from "element-plus";
import Helper from "./helper";

export default {
  components: {},
  setup() {
    const route = useRoute();
    const slug = route.params.slug;
    let formData = reactive(Helper.defaultFormData());

    let details = ref(null);
    let loading = ref(false);

    const { back } = useApplication();

    const { fetchTaskDetails } = useTask(loading);
    fetchTaskDetails(`tasks/${slug}`, details);
    const { longLocalDate, handleTimeAgo } = useDateTime();
    const { notify } = useNotification();

    const getUser = function () {
      return typeof formData.user === "object" && formData.user != null
        ? formData.user.id
        : formData.user;
    };

    return {
      details,
      loading,
      back,
      formData,
      longLocalDate,
      handleTimeAgo,
      fetchTaskDetails,
    };
  },
};
</script>
<style>
.task-details {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin: 15px 0px;
}
.time {
  background-color: #f1f1f1;
  font-weight: bold;
  padding: 2px 4px;
  border-radius: 4px;
}
</style>
