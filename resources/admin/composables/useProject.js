import { useFetch } from ".";
export default function (loading) {
  const { fetchData } = useFetch(loading);
  const fetchProjectDetails = async function (url, details) {
    const data = await fetchData(url);
    if (data) {
      details.value = data.project;
    }
  };
  const fetchProjects = async function (url, projects, obj = null) {
    const data = await fetchData(url, obj);
    if (data) {
      projects.value = data.projects;
    }
  };
  return { fetchProjectDetails, fetchProjects };
}
