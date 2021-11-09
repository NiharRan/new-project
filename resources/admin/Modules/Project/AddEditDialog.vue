<template>
  <el-dialog
    title="Add  Project"
    v-model="visible"
    width="30%"
    @click="$emit('close', false)"
  >
    <div style="margin-bottom: 15px">
      <label for="name">Name</label>
      <el-input
        name="name"
        placeholder="Project name"
        required
        v-model="form.name"
      ></el-input>
    </div>

    <div style="margin-bottom: 15px">
      <label for="users">Asigned To</label>
      <el-select
        v-model="form.users"
        multiple
        placeholder="Select"
        value-key="id"
      >
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
  props: ["editable", "visible", "form", "errors", "users"],
  emits: ["close", "save"],
};
</script>
