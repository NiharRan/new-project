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

      <div class="comments">
        <div style="margin: 15px 0px">
          <textarea
            :rows="rows"
            style="width: 100%"
            v-model="comment"
            placeholder="Make a comment"
            @focus="expendCommentBox"
          ></textarea>

          <div v-if="isReady">
            <el-button @click="hideCommentBox">Cancel</el-button>
            <el-button @click="saveComment(0)" type="success">Save</el-button>
          </div>
        </div>
        <div v-if="comments && comments.data && comments.data.length > 0">
          <div
            class="comment-block"
            v-for="(comment, key) in comments.data"
            :key="key"
          >
            <div class="commont-avatar">
              <span class="avatar"></span>
            </div>
            <div>
              <div class="comment-info">
                <router-link class="comment-author" :to="comment.user.link">{{
                  comment.user.full_name
                }}</router-link>
              </div>
              <p>
                <span
                  ><strong>{{ comment.total_replies }}</strong> replies,
                  <strong>{{ handleTimeAgo(comment.created_at) }}</strong></span
                >
              </p>
              <p class="text-sm">{{ comment.message }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </el-main>
</template>

<script>
import { useRoute } from "vue-router";
import { ref, reactive, inject } from "vue";
import {
  useNotification,
  useDateTime,
  useApplication,
  useError,
  useModal,
} from "@/admin/Bits/Composables";
import { useTask } from "../../composables";
import { ElNotification } from "element-plus";
import Helper from "./helper";

export default {
  components: {},
  setup() {
    const $rest = inject("$rest");
    const route = useRoute();
    const slug = route.params.slug;
    let formData = reactive(Helper.defaultFormData());
    let errors = ref(null);

    let details = ref(null);
    let loading = ref(false);
    let comment = ref("");
    let comments = ref(null);
    let isReady = ref(false);
    let editable = ref(false);
    let visible = ref(false);
    let rows = ref(1);

    const { handleErrors } = useError(errors);
    const { handleModal } = useModal(visible, editable);

    const { back } = useApplication();

    const { fetchTaskDetails, loadComments, storeCommentInfo } =
      useTask(loading);
    fetchTaskDetails(`tasks/${slug}`, details);
    setTimeout(() => {
      loadComments(`comments`, comments, { task: details.value.id });
    }, 2000);

    const { longLocalDate, handleTimeAgo } = useDateTime();
    const { notify } = useNotification();

    const getUser = function () {
      return typeof formData.user === "object" && formData.user != null
        ? formData.user.id
        : formData.user;
    };

    const saveComment = async function (parentId) {
      const url =
        editable.value == true ? `comments/${formData.id}` : "comments";
      const formData = {
        message: comment.value,
        user: Math.floor(Math.random() * 2) + 1,
        task: details.value.id,
        parent: parentId,
      };

      try {
        let response = null;
        if (editable.value == true) {
          response = await $rest.put(url, formData);
        } else {
          response = await $rest.post(url, formData);
        }
        if (response) {
          handleModal(false);
          hideCommentBox();
          loadComments("comments", comments, { task: details.value.id });
          ElNotification(notify("Comment", response.message, "success"));
        }
      } catch (error) {
        handleErrors(error);
        ElNotification(notify("Oops", error.statusText, "error"));
      }
    };

    const expendCommentBox = function () {
      isReady.value = true;
      rows.value = 4;
    };
    const hideCommentBox = function () {
      isReady.value = false;
      rows.value = 1;
      comment.value = "";
    };

    return {
      details,
      loading,
      comment,
      comments,
      rows,
      isReady,
      errors,
      editable,

      back,
      formData,
      longLocalDate,
      handleTimeAgo,
      fetchTaskDetails,
      expendCommentBox,
      hideCommentBox,
      loadComments,
      saveComment,
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
.comment-block {
  display: flex;
  align-items: flex-start;
  margin-bottom: 10px;
}
.comment-block:last-child {
  margin-bottom: 0;
}
.commont-avatar {
  height: 40px;
  width: 40px;
  background: #e1e1e1;
  border-radius: 50%;
  margin-right: 10px;
  overflow: hidden;
}
.commont-avatar .avatar {
  width: 100%;
  height: 100%;
}
.comments {
  width: 800px;
  border: 1px solid #e1e1e1;
  border-radius: 6px;
  box-shadow: 4px 4px 6px 1px rgb(0 0 0 / 5%);
  padding: 10px;
  margin-top: 10px;
}
.comment-author {
  font-size: 18px;
}
.comment-info {
  display: flex;
  align-items: center;
  justify-content: space-between;
}
</style>
