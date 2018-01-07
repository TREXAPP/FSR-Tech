/* On click delete volunteer (in Volunteers) fill the popup with hidden id field */
$('.delete-volunteer-button').on('click', function () {
  var id = this.id.replace("delete-volunteer-button-", "");
  /* Fill form with hidden elements  */
  $("#delete-volunteer-form").append("<input class='dynamic-input-element-popup' type='hidden' name='volunteer_id' value='" + id + "'>");
});

//on dismiss, remove all dynamic input elements from popup
$('.modal').on('hide.bs.modal', function () {
  $('.dynamic-input-element-popup').remove();
})
