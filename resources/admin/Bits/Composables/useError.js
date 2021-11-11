const useError = function () {
  const handleError = function (response) {
    if (response.responseJSON) {
      response = response.responseJSON;
    }
    let errorMessage = "";
    if (typeof response === "string") {
      errorMessage = response;
    } else if (response && response.message) {
      errorMessage = response.message;
    } else {
      errorMessage = convertToText(response);
    }
    if (!errorMessage) {
      errorMessage = "Something is wrong!";
    }
  };

  const convertToText = function (obj) {
    const string = [];
    if (typeof obj === "object" && obj.join === undefined) {
      for (const prop in obj) {
        string.push(convertToText(obj[prop]));
      }
    } else if (typeof obj === "object" && !(obj.join === undefined)) {
      for (const prop in obj) {
        string.push(convertToText(obj[prop]));
      }
    } else if (typeof obj === "function") {
    } else if (typeof obj === "string") {
      string.push(obj);
    }

    return string.join("<br />");
  };

  return {
    handleError,
    convertToText,
  };
};

export default useError;
