/* On click delete region fill the popup with hidden id field */
$('.delete-region-button').on('click', function () {
  var id = this.id.replace("delete-region-button-", "");
  /* Fill form with hidden elements  */
  $("#delete-region-form").append("<input class='dynamic-input-element-popup' type='hidden' name='region_id' value='" + id + "'>");
});

//on dismiss, remove all dynamic input elements from popup
$('.modal').on('hide.bs.modal', function () {
  $('.dynamic-input-element-popup').remove();
});
