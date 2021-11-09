<template>
  <el-dialog
    title="Add Task"
    v-model="visible"
    width="30%"
    @click="$emit('close', false)"
  >
    <div>
      <label for="name">Name</label>
      <el-input
        name="name"
        placeholder="Task name"
        required
        v-model="form.name"
      ></el-input>
    </div>

    <div v-if="!hideColumns.project">
      <label for="project">Project</label>
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
    </div>

    <div v-if="!hideColumns.user">
      <label for="user">User</label>
      <el-select v-model="form.user" placeholder="Select" value-key="id">
        <el-option
          v-for="item in users"
          :key="item.id"
          :label="item.full_name"
          :value="item"
        >
        </el-option>
      </el-select>
    </div>

    <span slot="footer" class="dialog-footer">
      <el-button @click="$emit('close', false)">Cancel</el-button>
      <el-button type="primary" @click="$emit('save')">Confirm</el-button>
    </span>
  </el-dialog>
</template>

<script>
export default {
  props: {
    editable: { type: Boolean, default: false },
    visible: { type: Boolean, default: false },
    form: { type: Object, default: () => null },
    projects: { type: Object, default: () => null },
    users: { type: Object, default: () => null },
    hideColumns: { type: Object, default: () => {} },
  },
  emits: ["close", "save", "fetchUsers"],
};
</script>
