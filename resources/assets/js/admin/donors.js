/* On click delete donor fill the popup with hidden id field */
$('.delete-donor-button').on('click', function () {
  var id = this.id.replace("delete-donor-button-", "");
  /* Fill form with hidden elements  */
  $("#delete-donor-form").append("<input class='dynamic-input-element-popup' type='hidden' name='donor_id' value='" + id + "'>");
});

//on dismiss, remove all dynamic input elements from popup
$('.modal').on('hide.bs.modal', function () {
  $('.dynamic-input-element-popup').remove();
});
