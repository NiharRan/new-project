import moment from "moment";
const appStartTime = new Date();

const useDateTime = function () {
  const dateTimeFormat = function (date, format) {
    const dateString = date === undefined ? null : date;
    const dateObj = moment(dateString);
    return dateObj.isValid() ? dateObj.format(format) : null;
  };

  const localDate = function (date) {
    return moment.utc(date).local();
  };

  const longLocalDate = function (date) {
    return dateTimeFormat(date, "ddd, DD MMM, YYYY");
  };

  const longLocalDateTime = function (date) {
    return dateTimeFormat(date, "ddd, DD MMM, YYYY hh:mm:ssa");
  };

  const humanDiffTime = function (date) {
    const dateString = date === undefined ? null : date;
    if (!dateString) {
      return "";
    }
    const endTime = new Date();
    const timeDiff = endTime - appStartTime; // in ms
    const dateObj = moment(dateString);
    return dateObj.from(
      moment(window.fluentFrameworkAdmin.server_time).add(
        timeDiff,
        "milliseconds"
      )
    );
  };

  const waitingTime = function (time1, time2) {
    if (!time2 || !time1) {
      return "";
    }
    time1 = moment(time1);
    time2 = moment(time2);
    return time2.from(time1);
  };

  return {
    dateTimeFormat,
    localDate,
    longLocalDate,
    longLocalDateTime,
    humanDiffTime,
    waitingTime,
  };
};

export default useDateTime;
