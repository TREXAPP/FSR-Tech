/* On click delete location fill the popup with hidden id field */
$('.delete-location-button').on('click', function () {
  var id = this.id.replace("delete-location-button-", "");
  /* Fill form with hidden elements  */
  $("#delete-location-form").append("<input class='dynamic-input-element-popup' type='hidden' name='location_id' value='" + id + "'>");
});

//on dismiss, remove all dynamic input elements from popup
$('.modal').on('hide.bs.modal', function () {
  $('.dynamic-input-element-popup').remove();
});
