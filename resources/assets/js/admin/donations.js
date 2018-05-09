/* On click delete location fill the popup with hidden id field */
$('.admin-listing-delete').on('click', function () {
  var id = this.id.replace("admin-listing-delete-", "");
  /* Fill form with hidden elements  */
  $("#delete-listing-form").append("<input class='dynamic-input-element-popup' type='hidden' name='listing_id' value='" + id + "'>");
});

//on dismiss, remove all dynamic input elements from popup
$('.modal').on('hide.bs.modal', function () {
  $('.dynamic-input-element-popup').remove();
});
