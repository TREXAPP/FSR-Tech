/* On click delete transport-type fill the popup with hidden id field */
$('.delete-transport-type-button').on('click', function () {
  var id = this.id.replace("delete-transport-type-button-", "");
  /* Fill form with hidden elements  */
  $("#delete-transport-type-form").append("<input class='dynamic-input-element-popup' type='hidden' name='transport_type_id' value='" + id + "'>");
});

//on dismiss, remove all dynamic input elements from popup
$('.modal').on('hide.bs.modal', function () {
  $('.dynamic-input-element-popup').remove();
});
