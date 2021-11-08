import moment from "moment";
export default function () {
  const formatDate = function (date) {
    return moment(date).format("DD-MM-YYYY");
  };

  return {
    formatDate,
  };
}
