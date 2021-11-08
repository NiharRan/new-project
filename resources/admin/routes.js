import Dashboard from "./Components/Dashboard.vue";
import Project from "./Modules/Project/Index.vue";
import ProjectDetails from "./Modules/Project/Details.vue";
import Task from "./Modules/Task/Index.vue";
import User from "./Modules/User/Index.vue";

export default [
  {
    path: "/",
    name: "dashboard",
    component: Dashboard,
    meta: {
      active: "dashboard",
    },
  },
  {
    path: "/projects",
    name: "project",
    component: Project,
  },
  {
    path: "/projects/:id",
    name: "projectDetails",
    component: ProjectDetails,
  },
  {
    path: "/tasks",
    name: "task",
    component: Task,
  },
  {
    path: "/users",
    name: "user",
    component: User,
  },
];
