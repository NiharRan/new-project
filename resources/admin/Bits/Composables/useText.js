const useText = function () {
  const ucFirst = function (text) {
    return text[0].toUpperCase() + text.slice(1).toLowerCase();
  };

  const ucWords = function (text) {
    return (text + "").replace(/^(.)|\s+(.)/g, function ($1) {
      return $1.toUpperCase();
    });
  };

  const slugify = function (text) {
    return text
      .toString()
      .toLowerCase()
      .replace(/\s+/g, "-") // Replace spaces with -
      .replace(/[^\w\\-]+/g, "") // Remove all non-word chars
      .replace(/\\-\\-+/g, "-") // Replace multiple - with single -
      .replace(/^-+/, "") // Trim - from start of text
      .replace(/-+$/, ""); // Trim - from end of text
  };

  return {
    ucFirst,
    ucWords,
    slugify,
  };
};

export default useText;
