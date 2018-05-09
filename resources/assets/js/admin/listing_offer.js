//admin accepted listing - edit comment
$('.admin-edit-comment-button').on('click', function () {
  var comment_id = this.id.replace('admin-edit-comment-button-', '');
  var controls = $(this).closest('.admin-comment-controls');
  var listing_offer = $(this).closest('.admin-comment-controls')[0].id.replace('admin-comment-controls-', '');
  $("#popup-hidden-edit-comment-id").val(comment_id);
  $("#popup-hidden-edit-listing-offer-id").val(listing_offer);
  $('#edit-comment-text').text($('#comment-text-' + comment_id).text().trim());
});

//admin accepted listing - delete comment
$('.admin-delete-comment-button').on('click', function() {
    var comment_id = this.id.replace('admin-delete-comment-button-','');
    var controls = $(this).closest('.admin-comment-controls');
    var listing_offer = $(this).closest('.admin-comment-controls')[0].id.replace('admin-comment-controls-','');
    $("#popup-hidden-delete-comment-id").val(comment_id);
    $("#popup-hidden-delete-listing-offer-id").val(listing_offer);
});
