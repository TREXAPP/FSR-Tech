$('#food_type_select_admin').on('change', function() {

  //remove all from organizations select box (except the default one)
  $('#product_id_select_admin').children().remove();
  $('#quantity_type_admin').children().remove();

  //get the products retrieved from the database with ajax
  $.post('products', {'food_type': this.value, '_token':$('meta[name="csrf-token"]').attr('content')}, function(data) {
    if (data) {
      //append the other options retrieved from database
      var first_product = null;
      $.each(data,function(key, value) {
        if (!first_product) {
          first_product = value.id;
        }
        $('#product_id_select_admin').append('<option value=' + value.id + '>' + value.name + '</option>');
      });

      //get the quantity_types for the first product retrieved from the database with ajax
      $.post('quantity_types', {'product_id': first_product, '_token':$('meta[name="csrf-token"]').attr('content')}, function(data) {
        if (data) {
          console.log(data);
          //append the other options retrieved from database
          $.each(data,function(key, value) {
            if (value.pivot['default']) {
              $('#quantity_type_admin').append('<option value=' + value.id + ' selected>' + value.description + '</option>');
            } else {
              $('#quantity_type_admin').append('<option value=' + value.id + '>' + value.description + '</option>');
            }
          });
        }
      });
    }
  });
});

$('#product_id_select_admin').on('change', function() {
  $('#quantity_type_admin').children().remove();
  //get the quantity_types for the product retrieved from the database with ajax
  $.post('quantity_types', {'product_id': this.value, '_token':$('meta[name="csrf-token"]').attr('content')}, function(data) {
    if (data) {
      console.log(data);
      //append the other options retrieved from database
      $.each(data,function(key, value) {
        if (value.pivot['default']) {
          $('#quantity_type_admin').append('<option value=' + value.id + ' selected>' + value.description + '</option>');
        } else {
          $('#quantity_type_admin').append('<option value=' + value.id + '>' + value.description + '</option>');
        }
      });
    }
  });

});
