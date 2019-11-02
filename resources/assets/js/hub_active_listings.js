$('.hub-listing-details').on('click', function() {
  var listing_id = this.id.replace('hub-listing-details-','');

  var title = $('#hidden-product-name-' + listing_id).text();
  var quantity = $('#hidden-quantity-' + listing_id).text();
  var pickup_time = $('#hidden-pickup-time-' + listing_id).text();
  var listed = $('#hidden-listed-' + listing_id).text();
  var expires_in = $('#hidden-expires-in-' + listing_id).text();
  var food_type = $('#hidden-food-type-' + listing_id).text();
  var description = $('#hidden-description-' + listing_id).text();

  $('#details-popup-title').text(title);
  $('#details-popup-quantity-value').text(quantity);
  $('#details-popup-pickup-time-value').text(pickup_time);
  $('#details-popup-listed-value').text(listed);
  $('#details-popup-expires-in-value').text(expires_in);
  $('#details-popup-food-type-value').text(food_type);
  $('#details-popup-description-value').text(description);

});
