/* On click delete location fill the popup with hidden id field */
$('.admin-hub-listing-delete').on('click', function () {
  var id = this.id.replace("admin-hub-listing-delete-", "");
  /* Fill form with hidden elements  */
  $("#delete-hub-listing-form").append("<input class='dynamic-input-element-popup' type='hidden' name='hub_listing_id' value='" + id + "'>");
});

//on dismiss, remove all dynamic input elements from popup
$('.modal').on('hide.bs.modal', function () {
  $('.dynamic-input-element-popup').remove();
});
