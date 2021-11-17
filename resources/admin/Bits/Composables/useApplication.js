import { useRouter } from "vue-router";

const useApplication = function () {
  const setTitle = function (title) {
    document.title = title;
  };
  const router = useRouter();
  const back = function (url) {
    router.push(url);
  };

  return {
    setTitle,
    back,
  };
};

export default useApplication;
