export default function (visible, editable) {
  const handleModal = function (p_visible) {
    visible.value = p_visible;
    editable.value = false;
  };

  return {
    handleModal,
  };
}
