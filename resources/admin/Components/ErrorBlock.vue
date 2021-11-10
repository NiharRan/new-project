<template>
  <span class="invalid-feedback">{{ errors[field] ?? "" }}</span>
</template>

<script>
export default {
  props: {
    errors: {
      type: Object,
      default: () => {},
    },
    field: {
      type: String,
      default: "",
    },
  },
};
</script>
