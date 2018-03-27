/* On click delete cso-organization fill the popup with hidden id field */
$('.delete-cso-organization-button').on('click', function () {
  var id = this.id.replace("delete-cso-organization-button-", "");
  /* Fill form with hidden elements  */
  $("#delete-cso-organization-form").append("<input class='dynamic-input-element-popup' type='hidden' name='cso_organization_id' value='" + id + "'>");
  var users_no = $('#users-no-' + id).text();
  var volunteers_no = $('#volunteers-no-' + id).text();
  $('#delete-popup-users-no').text(users_no);
  $('#delete-popup-volunteers-no').text(volunteers_no);
});

//on dismiss, remove all dynamic input elements from popup
$('.modal').on('hide.bs.modal', function () {
  $('.dynamic-input-element-popup').remove();
  $('#delete-popup-users-no').text('');
  $('#delete-popup-volunteers-no').text('');
});
