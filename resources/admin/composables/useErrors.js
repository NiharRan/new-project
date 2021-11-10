export default function (errors) {
  const handleErrors = function (error) {
    if ("responseJSON" in error) {
      errors.value = error.responseJSON;
    } else {
      errors.value = error;
    }

    if (Array.isArray(errors.value)) {
      let objErrors = {};
      errors.value.forEach(function (row) {
        let key = Object.keys(row)[0];
        objErrors[key] = row[key];
      });
      errors.value = objErrors;
    }
  };

  return {
    handleErrors,
  };
}
