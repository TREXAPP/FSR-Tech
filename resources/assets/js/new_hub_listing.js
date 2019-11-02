$('#food_type_select_hub').on('change', function() {
  console.log('vlaga');
  $("#product_id_select_hub").prop('disabled', true);
  $("#quantity").val('');
  $("#quantity").prop('disabled', true);
  $("#quantity_type").prop('disabled', true);

  //remove all from organizations select box (except the default one)
  $('#product_id_select_hub').children('option:not(:first)').remove();
  $('#quantity_type').children().remove();

  //get the products retrieved from the database with ajax
  $.post('new_hub_listing/products', {'food_type': this.value, '_token':$('meta[name="csrf-token"]').attr('content')}, function(data) {
    if (data) {
      //append the other options retrieved from database
      $.each(data,function(key, value) {
        $('#product_id_select_hub').append('<option value=' + value.id + '>' + value.name + '</option>');
      });
      if ($('#food_type_select_hub').val()) {
        $("#product_id_select_hub").prop('disabled', false);
      }
    }
  });


});

$('#product_id_select_hub').on('change', function() {

  $("#quantity").prop('disabled', true);
    $("#quantity").val('');
  $("#quantity_type").prop('disabled', true);

  //remove all from quantity_type select box (except the default one)
  $('#quantity_type').children().remove();

  //get the quantity_types retrieved from the database with ajax
  if (this.value) {
  $.post('new_hub_listing/quantity_types', {'product': this.value, '_token':$('meta[name="csrf-token"]').attr('content')}, function(data) {
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
      if ($('#product_id_select_hub').val()) {
        $("#quantity").prop('disabled', false);
        $("#quantity_type").prop('disabled', false);
      }
    }

  });
}
});

$('#sell_by_date_hub').on('blur', function() {
  $('#expires_in').val(this.value);
  $('#expires_in').attr( "max", this.value );
});

$('#time_type_sell_by_hub').on('change', function() {
  var selected_value = this.value;
  $('#time_type').val(this.value);
  var found = false;
  $("#time_type > option").each(function() {
    if (found) {
      $(this).css('display', 'none');
    } else {
      $(this).css('display', 'block');
    }
    if (this.value == selected_value) {
      found = true;
    }
  });
});
