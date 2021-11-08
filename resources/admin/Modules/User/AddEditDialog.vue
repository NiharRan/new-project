<template>
  <el-dialog
    title="Add User"
    v-model="visible"
    width="30%"
    @click="$emit('close', false)"
  >
    <div style="margin-bottom: 15px">
      <label for="name">First Name</label>
      <el-input
        name="name"
        placeholder="First name"
        required
        v-model="form.first_name"
      ></el-input>
    </div>
    <div style="margin-bottom: 15px">
      <label for="name">Last Name</label>
      <el-input
        name="name"
        placeholder="Last name"
        required
        v-model="form.last_name"
      ></el-input>
    </div>
    <div style="margin-bottom: 15px">
      <label for="email">E-mail</label>
      <el-input
        name="email"
        placeholder="E-mail address"
        required
        v-model="form.email"
      ></el-input>
    </div>
    <div style="margin-bottom: 15px">
      <label for="phone">Phone</label>
      <el-input
        name="phone"
        placeholder="Contact No."
        required
        v-model="form.phone"
      ></el-input>
    </div>

    <span slot="footer" class="dialog-footer">
      <el-button @click="$emit('close', false)">Cancel</el-button>
      <el-button type="primary" @click="$emit('save')">Confirm</el-button>
    </span>
  </el-dialog>
</template>

<script>
export default {
  props: ["editable", "visible", "form", "errors"],
  emits: ["close", "save"],
};
</script>
