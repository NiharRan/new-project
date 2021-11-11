const useStorage = function () {
  const getData = function (key, defaultValue = false) {
    let existingData = window.localStorage.getItem("__fluentsupport_data");
    existingData = JSON.parse(existingData);
    if (!existingData) {
      return defaultValue;
    }

    if (existingData[key]) {
      return existingData[key];
    }

    return defaultValue;
  };
  const saveData = function (key, data) {
    let existingData = window.localStorage.getItem("__fluentsupport_data");

    if (!existingData) {
      existingData = {};
    } else {
      existingData = JSON.parse(existingData);
    }

    existingData[key] = data;

    window.localStorage.setItem(
      "__fluentsupport_data",
      JSON.stringify(existingData)
    );
  };
  return {
    getData,
    saveData,
  };
};

export default useStorage;
