/* On click delete cso fill the popup with hidden id field */
$('.delete-cso-button').on('click', function () {
  var id = this.id.replace("delete-cso-button-", "");
  /* Fill form with hidden elements  */
  $("#delete-cso-form").append("<input class='dynamic-input-element-popup' type='hidden' name='cso_id' value='" + id + "'>");
});

//on dismiss, remove all dynamic input elements from popup
$('.modal').on('hide.bs.modal', function () {
  $('.dynamic-input-element-popup').remove();
});
