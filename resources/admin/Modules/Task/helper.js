const defaultFormData = function (project = null, user = null) {
  return {
    index: "",
    project: project,
    user: user,
    name: "",
    id: "",
    status: "",
  };
};

export default {
  defaultFormData,
};
