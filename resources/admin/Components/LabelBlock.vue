<template>
  <label class="label-block"
    >{{ text }}
    <strong v-if="isRequired" style="color: #df3737">*</strong></label
  >
</template>

<script>
export default {
  props: {
    text: {
      type: String,
      default: "",
    },
    isRequired: {
      type: Boolean,
      default: false,
    },
  },
};
</script>

<style>
.label-block {
  margin-top: 10px;
  display: flex;
  align-items: center;
}
</style>
