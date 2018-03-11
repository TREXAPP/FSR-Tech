/* On click delete quantity-type fill the popup with hidden id field */
$('.delete-quantity-type-button').on('click', function () {
  var id = this.id.replace("delete-quantity-type-button-", "");
  /* Fill form with hidden elements  */
  $("#delete-quantity-type-form").append("<input class='dynamic-input-element-popup' type='hidden' name='quantity_type_id' value='" + id + "'>");
});

//on dismiss, remove all dynamic input elements from popup
$('.modal').on('hide.bs.modal', function () {
  $('.dynamic-input-element-popup').remove();
});
