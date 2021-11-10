<template>
  <el-dialog
    title="Add  Project"
    v-model="visible"
    width="30%"
    @click="$emit('close', false)"
  >
    <div style="margin-bottom: 15px">
      <label-block class="mt-0" :text="'Name'" :isRequired="true" />
      <el-input
        name="name"
        placeholder="Project name"
        :class="[errors.name ? 'invalid' : '']"
        required
        v-model="form.name"
      ></el-input>
      <error-block :errors="errors" :field="'name'" />
    </div>

    <div style="margin-bottom: 15px">
      <label-block :text="'Asigned To'" :isRequired="true" />
      <el-select
        v-model="form.users"
        :class="[errors.users ? 'invalid' : '']"
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
      <error-block :errors="errors" :field="'users'" />
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
  props: ["editable", "visible", "form", "errors", "users"],
  emits: ["close", "save"],
  components: {
    ErrorBlock,
    LabelBlock,
  },
};
</script>
