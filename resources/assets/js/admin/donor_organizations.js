/* On click delete donor-organization fill the popup with hidden id field */
$('.delete-donor-organization-button').on('click', function () {
  var id = this.id.replace("delete-donor-organization-button-", "");
  /* Fill form with hidden elements  */
  $("#delete-donor-organization-form").append("<input class='dynamic-input-element-popup' type='hidden' name='donor_organization_id' value='" + id + "'>");
  var users_no = $('#users-no-' + id).text();
  $('#delete-popup-users-no').text(users_no);
});

//on dismiss, remove all dynamic input elements from popup
$('.modal').on('hide.bs.modal', function () {
  $('.dynamic-input-element-popup').remove();
  $('#delete-popup-users-no').text('');
});
