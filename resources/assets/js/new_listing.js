//register
$('#food_type_select').on('change', function() {

  //enable disable fields
  if (this.value) {
      $("#product_id_select").prop('disabled', false);

  } else {
          $("#product_id_select").prop('disabled', true);
  }

  //remove all from organizations select box (except the default one)
  $('#product_id_select').children('option:not(:first)').remove();

  //get the organizations retrieved from the database with ajax
  $.post('new_listing/products', {'food_type': this.value, '_token':$('meta[name="csrf-token"]').attr('content')}, function(data) {
    if (data) {
      //append the other options retrieved from database
      $.each(data,function(key, value) {
        $('#product_id_select').append('<option value=' + value.id + '>' + value.name + '</option>');
      });
    }
  });
});
