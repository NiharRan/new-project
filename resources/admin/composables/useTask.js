import { useFetch } from ".";
export default function (loading) {
  const { fetchData } = useFetch(loading);
  const fetchTaskDetails = async function (url, details) {
    const data = await fetchData(url);
    if (data) {
      details.value = data.task;
    }
  };
  const fetchTasks = async function (url, tasks, obj) {
    const data = await fetchData(url, (obj = null));
    if (data) {
      tasks.value = data.tasks;
    }
  };
  return { fetchTaskDetails, fetchTasks };
}
