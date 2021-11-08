const defaultFormData = function (project = null) {
  return {
    index: "",
    project: project,
    name: "",
    id: "",
    status: "",
  };
};

export default {
  defaultFormData,
};
