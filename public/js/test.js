$('#product_id_select').on('change', function() {

  //enable disable fields
  if (this.value) {
      $("#product_id_select").prop('disabled', false);

  } else {
          $("#quantity").prop('disabled', true);
          $("#quantity_type").prop('disabled', true);
  }

  //remove all from quantity_type select box (except the default one)
  $('#quantity_type').children().remove();

  //get the quantity_types retrieved from the database with ajax
  if (this.value) {
  $.post('new_listing/quantity_types', {'product': this.value, '_token':$('meta[name="csrf-token"]').attr('content')}, function(data) {
    if (data) {
      //append the other options retrieved from database
      $.each(data,function(key, value) {
        if (value.pivot['default']) {
          $('#quantity_type').append('<option value=' + value.id + ' selected>' + value.description + '</option>');
        } else {
          $('#quantity_type').append('<option value=' + value.id + '>' + value.description + '</option>');
        }
        if (data.length <= 1) {
          $("#quantity_type").prop('disabled', true);
        } else {
          $("#quantity_type").prop('disabled', false);
        }
      });
      $("#quantity").prop('disabled', false);
    }
  });
}
});
