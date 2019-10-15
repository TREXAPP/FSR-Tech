/* On click delete hub fill the popup with hidden id field */
$('.delete-hub-button').on('click', function () {
  var id = this.id.replace("delete-hub-button-", "");
  /* Fill form with hidden elements  */
  $("#delete-hub-form").append("<input class='dynamic-input-element-popup' type='hidden' name='hub_id' value='" + id + "'>");
});

//on dismiss, remove all dynamic input elements from popup
$('.modal').on('hide.bs.modal', function () {
  $('.dynamic-input-element-popup').remove();
});
