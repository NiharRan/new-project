const useApplication = function () {
  const setTitle = function (title) {
    document.title = title;
  };

  return {
    setTitle,
  };
};

export default useApplication;
