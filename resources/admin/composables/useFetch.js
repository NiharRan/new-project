import Rest from "@/admin/Bits/Rest";

export default function (loading) {
  const fetchData = async function (url, params = {}) {
    loading.value = true;
    const response = await Rest.get(url, params);
    loading.value = false;
    return response;
  };

  return {
    fetchData,
  };
}
