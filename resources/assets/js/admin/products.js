/* On click delete product fill the popup with hidden id field */
$('.delete-product-button').on('click', function () {
  var id = this.id.replace("delete-product-button-", "");
  /* Fill form with hidden elements  */
  $("#delete-product-form").append("<input class='dynamic-input-element-popup' type='hidden' name='product_id' value='" + id + "'>");
});

//on dismiss, remove all dynamic input elements from popup
$('.modal').on('hide.bs.modal', function () {
  $('.dynamic-input-element-popup').remove();
});
