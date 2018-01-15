$('#organization_type_select').on('change', function() {

  //enable disable fields
  if (this.value) {
      $("input").prop('disabled', false);
      $("#name").prop('disabled', false);
      $("#description").prop('disabled', false);
      $("#working_hours_from").prop('disabled', false);
      $("#working_hours_to").prop('disabled', false);
      $("#image").prop('disabled', false);
      $("button").prop('disabled', false);

  } else {
    $("input").prop('disabled', true);
    $("#name").prop('disabled', true);
    $("#description").prop('disabled', true);
    $("#working_hours_from").prop('disabled', true);
    $("#working_hours_to").prop('disabled', true);
    $("#image").prop('disabled', true);
    $("button").prop('disabled', true);
  }

});
