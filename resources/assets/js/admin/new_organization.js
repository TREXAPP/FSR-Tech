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

  //show hide working hours
  if (this.value == 'donor') {

    $('.working-hours-from').css('display', 'initial');
    $('.working-hours-from').css('visibility', 'initial');
    $('#working_hours_from').prop('required',true);

    $('.working-hours-to').css('display', 'initial');
    $('.working-hours-to').css('visibility', 'initial');
    $('#working_hours_to').prop('required',true);

  } else {
    $('.working-hours-from').css('display','none');
    $('.working-hours-from').css('visibility', 'hidden');
    $('#working_hours_from').prop('required',false);

    $('.working-hours-to').css('display','none');
    $('.working-hours-to').css('visibility', 'hidden');
    $('#working_hours_to').prop('required',false);
  }

});
