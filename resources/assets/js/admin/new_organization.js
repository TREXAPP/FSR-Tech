$('#organization_type_select').on('change', function() {

  //enable disable fields
  if (this.value) {
      $("input").prop('disabled', false);
      $("#donor_type").prop('disabled', false);
      $("#name").prop('disabled', false);
      $("#address").prop('disabled', false);
      $("#description").prop('disabled', false);
      $("#working_hours_from").prop('disabled', false);
      $("#working_hours_to").prop('disabled', false);
      $("#image").prop('disabled', false);
      $("button").prop('disabled', false);


  } else {
    $("input").prop('disabled', true);
    $("#donor_type").prop('disabled', true);
    $("#name").prop('disabled', true);
    $("#address").prop('disabled', true);
    $("#description").prop('disabled', true);
    $("#working_hours_from").prop('disabled', true);
    $("#working_hours_to").prop('disabled', true);
    $("#image").prop('disabled', true);
    $("button").prop('disabled', true);
  }

  //show hide working hours and donor_type
  if (this.value == 'donor') {

    $('.working-hours-from').css('display', 'block');
    $('.working-hours-from').css('visibility', 'initial');
    $('#working_hours_from').prop('required',true);

    $('.working-hours-to').css('display', 'block');
    $('.working-hours-to').css('visibility', 'initial');
    $('#working_hours_to').prop('required',true);

    $('.donor_type').css('display', 'block');
    $('.donor_type').css('visibility', 'initial');
    $('#donor_type').prop('required',true);

  } else {
    $('.working-hours-from').css('display','none');
    $('.working-hours-from').css('visibility', 'hidden');
    $('#working_hours_from').prop('required',false);

    $('.working-hours-to').css('display','none');
    $('.working-hours-to').css('visibility', 'hidden');
    $('#working_hours_to').prop('required',false);

    $('.donor_type').css('display', 'none');
    $('.donor_type').css('visibility', 'hidden');
    $('#donor_type').prop('required',false);
  }

});
