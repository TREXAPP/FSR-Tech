/* On click delete food-type fill the popup with hidden id field */
$('.delete-food-type-button').on('click', function () {
  var id = this.id.replace("delete-food-type-button-", "");
  /* Fill form with hidden elements  */
  $("#delete-food-type-form").append("<input class='dynamic-input-element-popup' type='hidden' name='food_type_id' value='" + id + "'>");
});

//on dismiss, remove all dynamic input elements from popup
$('.modal').on('hide.bs.modal', function () {
  $('.dynamic-input-element-popup').remove();
});
