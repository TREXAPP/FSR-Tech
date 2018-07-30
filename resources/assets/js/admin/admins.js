$('.delete-admin-button').on('click', function () {
  var id = this.id.replace("delete-admin-button-", "");
  /* Fill form with hidden elements  */
  $("#delete-admin-form").append("<input class='dynamic-input-element-popup' type='hidden' name='admin_id' value='" + id + "'>");
});
