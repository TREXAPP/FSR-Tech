/* on change users type, fill organizations dropdown */
$('#user-type-filter-select').on('change', function() {
  $('#organization-filter-select').children('option:not(:first)').remove();
  $('#user-filter-select').children('option:not(:first)').remove();

  $('#organization-filter-select').prop('disabled', true);
  $('#user-filter-select').prop('disabled', true);



    //  zemi so ajax vrednosti za organizacijata
    $.post('email/organizations', {'users_type': this.value, '_token':$('meta[name="csrf-token"]').attr('content')}, function(data) {
      if (data) {
        //  popolni gi soodvetnite polinja

        $.each(data, function (key, value) {
          $('#organization-filter-select').append('<option value=' + value.id + '>' + value.name + '</option>');
        });
        $('#organization-filter-select').prop('disabled', false);
      }
      refresh_email_counter();
    });
    if (!this.value) {
      //disable organization-filter and user-filter
      $('#organization-filter-select').prop('disabled', true);
      $('#user-filter-select').prop('disabled', true);
    }
});

/* on change organizations, fill user dropdown */
$('#organization-filter-select').on('change', function() {
    $('#user-filter-select').children('option:not(:first)').remove();
    $('#user-filter-select').prop('disabled', true);


        //  zemi so ajax vrednosti za userot
        $.post('email/users', {'organization_id': this.value, 'users_type': $('#user-type-filter-select').val(), '_token':$('meta[name="csrf-token"]').attr('content')}, function(data) {
          if (data) {
            //  popolni gi soodvetnite polinja
            $.each(data, function (key, value) {
              $('#user-filter-select').append('<option value=' + value.id + '>' + value.first_name + ' ' + value.last_name + '</option>');
            });
            $('#user-filter-select').prop('disabled', false);
          }
          refresh_email_counter();
        });
      if (!this.value) {
        //disable organization-filter and user-filter
        $('#user-filter-select').prop('disabled', true);
      }
});

/* on change user, refresh counter */
$('#user-filter-select').on('change', function() {
  refresh_email_counter();
});

function refresh_email_counter() {
  //  zemi so ajax counteri
  $.post('email/counters', {
      'users_type': $('#user-type-filter-select').val(),
      'organization_id': $('#organization-filter-select').val(),
      'user_id': $('#user-filter-select').val(),
      '_token':$('meta[name="csrf-token"]').attr('content')},
      function(data) {
        if (data) {
          //  popolni gi soodvetnite polinja
          $('#donors-counter').text(data.donors_counter);
          $('#csos-counter').text(data.csos_counter);
          $('#hubs-counter').text(data.hubs_counter);
          $('#volunteers-counter').text(data.volunteers_counter);

          var total_users = data.donors_counter + data.csos_counter + data.volunteers_counter + data.hubs_counter;
          if (total_users == 0) {
            $('#send-email-button').prop('disabled', true);
            $('#send-email-button').prop('title', 'Изберете барем еден корисник за праќање емаил');
          } else {
            $('#send-email-button').prop('disabled', false);
              $('#send-email-button').prop('title', 'Прати емаил');
          }
        }
      });
}
