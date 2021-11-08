export default function () {
  const notify = function (title, message, type) {
    return {
      title,
      message,
      type,
      position: "bottom-right",
    };
  };

  return { notify };
}
