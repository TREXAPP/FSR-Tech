$('#food_type_select').on('change', function() {
  $("#product_id_select").prop('disabled', true);
  $("#quantity").val('');
  $("#quantity").prop('disabled', true);
  $("#quantity_type").prop('disabled', true);

  //remove all from organizations select box (except the default one)
  $('#product_id_select').children('option:not(:first)').remove();
  $('#quantity_type').children().remove();

  //get the products retrieved from the database with ajax
  $.post('new_listing/products', {'food_type': this.value, '_token':$('meta[name="csrf-token"]').attr('content')}, function(data) {
    if (data) {
      //append the other options retrieved from database
      $.each(data,function(key, value) {
        $('#product_id_select').append('<option value=' + value.id + '>' + value.name + '</option>');
      });
      if ($('#food_type_select').val()) {
        $("#product_id_select").prop('disabled', false);
      }
    }
  });


});

$('#product_id_select').on('change', function() {

  $("#quantity").prop('disabled', true);
    $("#quantity").val('');
  $("#quantity_type").prop('disabled', true);

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
        if (data.length == 0) {
          $("#quantity_type").prop('disabled', true);
        } else {
          $("#quantity_type").prop('disabled', false);
        }
      });
      if ($('#product_id_select').val()) {
        $("#quantity").prop('disabled', false);
        $("#quantity_type").prop('disabled', false);
      }
    }

  });
}


});
