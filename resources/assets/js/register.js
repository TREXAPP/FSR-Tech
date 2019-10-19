//disable enable fields and fill organizations with ajax
$('#register_type_select').on('change', function() {

  $("#register_organization_select").prop('disabled', true);

  //enable disable fields
  if (this.value) {
      $("input").prop('disabled', false);
      $("#register_location_select").prop('disabled', false);
      $("button").prop('disabled', false);
      if (this.value === 'hub') {
        $(".location-group").hide();
      } else {
        $(".location-group").show();
      }

  } else {
      $("input").prop('disabled', true);
      $("#register_organization_select").prop('disabled', true);
      $("#register_location_select").prop('disabled', true);
      $("button").prop('disabled', true);
  }

  //remove all from organizations select box (except the default one)
  $('#register_organization_select').children('option:not(:first)').remove();
  //get the organizations retrieved from the database with ajax
  $.post('register/organizations', {'type': this.value, '_token':$('meta[name="csrf-token"]').attr('content')}, function(data) {
    if (data) {
      //append the other options retrieved from database
      $.each(data,function(key, value) {
        $('#register_organization_select').append('<option value=' + value.id + '>' + value.name + '</option>');
      });
    }
    if ($('#register_type_select').val()) {
      $("#register_organization_select").prop('disabled', false);
    }
  });
});


//fill address field
$('#register_organization_select').on('change', function() {
$('#address').val('');
  //get the address for this organization retrieved from the database with ajax
  $.post('register/organizations/address/' + this.value, {'type': this.value, '_token':$('meta[name="csrf-token"]').attr('content')}, function(data) {
    if (data) {
      //append the other options retrieved from database
        $('#address').val(data);
      //  $('#register_organization_select').append('<option value=' + value.id + '>' + value.name + '</option>');

    }

  });
});
