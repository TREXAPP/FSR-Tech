//free_volunteers
$('#free_volunteer_type').on('change', function(){
  var selected_value = $( "#free_volunteer_type" ).val();
  if (selected_value == 'transport_food') {
    $('#transport_food_section').removeClass('hidden');
    $('#transport_types').attr('required', 'required');
    $('#organizations').attr('required', 'required');
  } else {
    $('#transport_food_section').addClass('hidden');
    $('#transport_types').removeAttr('required');
    $('#organizations').removeAttr('required');
  }
});

$("#availability_table").on("click", "input", function() {

  if (this.type == "checkbox") {
    this.checked = !this.checked;
  }
  // var chk = $( this.firstElementChild )[0];
  // chk.checked = !chk.checked;
    //console.log(chk[0].checked);
  });

$("#availability_table").on("click", "td", function() {
  var chk = $( this.firstElementChild )[0];
  if (typeof chk != 'undefined') {
    if (chk.type == "checkbox") {
        chk.checked = !chk.checked;
    }
  }
});
