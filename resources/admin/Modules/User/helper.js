export const defaultFormData = function () {
  return {
    index: "",
    first_name: "",
    last_name: "",
    email: "",
    phone: "",
    id: "",
    status: "",
  };
};

export const default_fields = [
  { name: "first_name", label: "First Name", link: true },
  { name: "last_name", label: "Last Name", link: true },
  { name: "created_at", label: "Date", link: false },
];

export const new_fields = [
  { name: "email", label: "E-mail", link: false },
  { name: "phone", label: "Phone", link: false },
];
