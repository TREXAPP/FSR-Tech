//set up change volunteer popup
$('.edit-volunteer-button').on('click', function() {
  var listing_offer_id = this.id.replace('edit-volunteer-button-','');
  $("#popup-hidden-listing-id").val(listing_offer_id);
  var volunteer_id = $('#volunteer-id-' + listing_offer_id).text();
  //  alert(volunteer_id);
  $("#popup-volunteer-select").val(volunteer_id);
})

//manage volunteer change
$("#update-volunteer-form").submit(function (e) {
  e.preventDefault(); // avoid to execute the actual submit of the form.
  var url = window.location.protocol + '//' + window.location.hostname + '/cso/accepted_listings/update_volunteer'; // Returns path only
  fd = new FormData(this);
  var listing_offer_id = $("#popup-hidden-listing-id").val();
  var old_volunteer_id = $('#volunteer-id-' + listing_offer_id).text();
  var new_volunteer_id = $("#popup-volunteer-select").val();
  var new_volunteer_text = $("#popup-volunteer-select option:selected").text();

  if (new_volunteer_id == old_volunteer_id) {
    $('#update-volunteer-popup').modal('hide');
  } else {
    //activate loading animation
    $('#popup-loading').addClass('fa');
    $('#popup-loading').addClass('fa-spinner');
    $('#popup-loading').addClass('fa-pulse');
    $('#popup-loading').addClass('fa-2x');
    $('#popup-loading').addClass('fa-fw');
    $.ajax({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      url: url, // Url to which the request is send
      type: "POST", // Type of request to be send, called as method
      data: new FormData(this), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
      contentType: false, // The content type used when sending data to the server.
      cache: false, // To unable request pages to be cached
      processData: false, // To send DOMDocument or non processed data file it is set to false
      success: function (data) // A function to be called if request succeeds
      {
        //disable loading animation
        $('#popup-loading').removeClass('fa');
        $('#popup-loading').removeClass('fa-spinner');
        $('#popup-loading').removeClass('fa-pulse');
        $('#popup-loading').removeClass('fa-2x');
        $('#popup-loading').removeClass('fa-fw');

        $('#volunteer-' + listing_offer_id).html('<strong>' + new_volunteer_text + '</strong>');
        $('#volunteer-id-' + listing_offer_id).text(new_volunteer_id);
        //close the popup
        $('#update-volunteer-popup').modal('hide');
      }
    });
  }
});

//single accepted listing - delete comment
$('.delete-comment-button').on('click', function() {
    var comment_id = this.id.replace('delete-comment-button-','');
    var controls = $(this).closest('.comment-controls');
    var listing_offer = $(this).closest('.comment-controls')[0].id.replace('comment-controls-','');
    $("#popup-hidden-delete-comment-id").val(comment_id);
    $("#popup-hidden-delete-listing-offer-id").val(listing_offer);
});

//single accepted listing - edit comment
$('.edit-comment-button').on('click', function() {
    var comment_id = this.id.replace('edit-comment-button-','');
    var controls = $(this).closest('.comment-controls');
    var listing_offer = $(this).closest('.comment-controls')[0].id.replace('comment-controls-','');
    //alert(listing_offer);
    $("#popup-hidden-edit-comment-id").val(comment_id);
    $("#popup-hidden-edit-listing-offer-id").val(listing_offer);
    $('#edit-comment-text').text($('#comment-text-' + comment_id).text().trim());
});
