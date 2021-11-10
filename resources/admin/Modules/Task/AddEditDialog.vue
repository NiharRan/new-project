<template>
  <el-dialog
    title="Add Task"
    v-model="visible"
    width="30%"
    @click="$emit('close', false)"
  >
    <div>
      <label-block class="mt-0" :text="'Name'" :isRequired="true" />
      <el-input
        name="name"
        placeholder="Task name"
        required
        v-model="form.name"
      ></el-input>
      <error-block :errors="errors" :field="'name'" />
    </div>

    <div v-if="!hideColumns.project">
      <label-block :text="'Project'" :isRequired="true" />
      <el-select
        v-model="form.project"
        placeholder="Select"
        value-key="id"
        @change="$emit('fetchUsers')"
      >
        <el-option
          v-for="item in projects"
          :key="item.id"
          :label="item.name"
          :value="item"
        >
        </el-option>
      </el-select>
      <error-block :errors="errors" :field="'project'" />
    </div>

    <div v-if="!hideColumns.user">
      <label-block :text="'User'" :isRequired="true" />
      <el-select v-model="form.user" placeholder="Select" value-key="id">
        <el-option
          v-for="item in users"
          :key="item.id"
          :label="item.full_name"
          :value="item"
        >
        </el-option>
      </el-select>
      <error-block :errors="errors" :field="'user'" />
    </div>

    <span slot="footer" class="dialog-footer">
      <el-button @click="$emit('close', false)">Cancel</el-button>
      <el-button type="primary" @click="$emit('save')">Confirm</el-button>
    </span>
  </el-dialog>
</template>

<script>
import ErrorBlock from "../../Components/ErrorBlock.vue";
import LabelBlock from "../../Components/LabelBlock.vue";
export default {
  components: { LabelBlock, ErrorBlock },
  props: {
    editable: { type: Boolean, default: false },
    visible: { type: Boolean, default: false },
    form: { type: Object, default: () => {} },
    projects: { type: Object, default: () => {} },
    users: { type: Object, default: () => {} },
    errors: { type: Object, default: () => {} },
    hideColumns: { type: Object, default: () => {} },
  },
  emits: ["close", "save", "fetchUsers"],
};
</script>
