<template>
  <el-dialog
    title="Add User"
    v-model="visible"
    width="30%"
    @click="$emit('close')"
  >
    <div style="margin-bottom: 15px">
      <label-block class="mt-0" :text="'First Name'" :isRequired="true" />
      <el-input
        name="name"
        :class="[errors.first_name ? 'invalid' : '']"
        placeholder="First name"
        required
        v-model="form.first_name"
      ></el-input>
      <error-block :errors="errors" :field="'first_name'" />
    </div>
    <div style="margin-bottom: 15px">
      <label-block :text="'Last Name'" :isRequired="true" />
      <el-input
        name="name"
        placeholder="Last name"
        :class="[errors.last_name ? 'invalid' : '']"
        required
        v-model="form.last_name"
      ></el-input>
      <error-block :errors="errors" :field="'last_name'" />
    </div>
    <div style="margin-bottom: 15px">
      <label-block :text="'E-mail'" :isRequired="true" />
      <el-input
        name="email"
        placeholder="E-mail address"
        :class="[errors.email ? 'invalid' : '']"
        required
        v-model="form.email"
      ></el-input>
      <error-block :errors="errors" :field="'email'" />
    </div>
    <div style="margin-bottom: 15px">
      <label-block :text="'Contact No.'" :isRequired="true" />
      <el-input
        name="phone"
        placeholder="Contact No."
        :class="[errors.phone ? 'invalid' : '']"
        required
        v-model="form.phone"
      ></el-input>
      <error-block :errors="errors" :field="'phone'" />
    </div>

    <span slot="footer" class="dialog-footer">
      <el-button @click="$emit('close')">Cancel</el-button>
      <el-button type="primary" @click="$emit('save')">Confirm</el-button>
    </span>
  </el-dialog>
</template>

<script>
import ErrorBlock from "../../Components/ErrorBlock.vue";
import LabelBlock from "../../Components/LabelBlock.vue";
export default {
  components: { ErrorBlock, LabelBlock },
  props: ["editable", "visible", "form", "errors"],
  emits: ["close", "save"],
};
</script>
