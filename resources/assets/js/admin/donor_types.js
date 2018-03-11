/* On click delete donor-type fill the popup with hidden id field */
$('.delete-donor-type-button').on('click', function () {
  var id = this.id.replace("delete-donor-type-button-", "");
  /* Fill form with hidden elements  */
  $("#delete-donor-type-form").append("<input class='dynamic-input-element-popup' type='hidden' name='donor_type_id' value='" + id + "'>");
});

//on dismiss, remove all dynamic input elements from popup
$('.modal').on('hide.bs.modal', function () {
  $('.dynamic-input-element-popup').remove();
});
