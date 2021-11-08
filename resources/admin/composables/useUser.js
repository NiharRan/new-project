import { useFetch } from ".";
export default function (loading) {
  const { fetchData } = useFetch(loading);
  const fetchUserDetails = async function (url, details) {
    const data = await fetchData(url);
    if (data) {
      details.value = data.user;
    }
  };
  const fetchUsers = async function (url, users, obj = null) {
    const data = await fetchData(url, obj);
    if (data) {
      users.value = data.users;
    }
  };
  return { fetchUserDetails, fetchUsers };
}
