import { inject } from "vue-demi";

export default function (loading) {
  const $rest = inject("$rest");
  const fetchData = async function (url, params = {}) {
    loading.value = true;
    const response = await $rest.get(url, params);
    loading.value = false;
    return response;
  };

  return {
    fetchData,
  };
}
