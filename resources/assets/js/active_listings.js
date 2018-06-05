/*  If there is error in server validation, scroll to the listing with the error */
$(document).ready(function () {
  if (document.getElementById(window.location.hash.substring(1))) {
    document.getElementById(window.location.hash.substring(1)).scrollIntoView();
  }
});

/* On quantity-needed input field change:
    - limit the value to the max_quantity
    - auto update the beneficiaries number input field in regards with the portion size
 */
$('.quantity-needed-input').on('input', function () {

  var id = this.id.replace('quantity-needed-', '');
  var max_quantity = $('#quantity-offered-' + id).text().split(' ')[0];
  var isnum = /^\d+$/.test(this.value);
  console.log(this.value);
  if ((this.value != '') && (parseInt(this.value) > max_quantity || !$.isNumeric(this.value))) {
    $(this).val(max_quantity);
  }

  var portion_size = $('#portion-size-' + id).text();
  $('#beneficiaries-no-' + id).val(parseInt(this.value / portion_size));
});

/* on selected volunteer, show his info */
$('.pickup-volunteer-name').on('change', function() {
  var listing_id = this.id.replace('pickup-volunteer-', '');
  var volunteer_info = $('#active-listings-volunteer-show-' + listing_id);
  if (this.value) {
    if (volunteer_info.hasClass('hidden')) {
      volunteer_info.removeClass('hidden');
    }
    //  zemi so ajax vrednosti za volonterot
    $.post('active_listings/get_volunteer', {'volunteer_id': this.value, '_token':$('meta[name="csrf-token"]').attr('content')}, function(data) {
      if (data) {
        //  popolni gi soodvetnite polinja

        $('#volunteer-info-first-name-value-' + listing_id).text(data.first_name);
        $('#volunteer-info-last-name-value-' + listing_id).text(data.last_name);
        $('#volunteer-info-email-value-' + listing_id).text(data.email);
        $('#volunteer-info-phone-value-' + listing_id).text(data.phone);
        $('#volunteer-info-image-' +  + listing_id).prop('src',data.image_url);
      }
    });



  } else {
    if (!volunteer_info.hasClass('hidden')) {
      volunteer_info.addClass('hidden');
    }
  }
});

/* When a listing is accepted, fill in the data in a popup for appearence, and fill in the form with hidden fields for sending */
$('.listing-submit-button').on('click', function () {
  var id = this.id.replace("listing-submit-button-", "");

  /* Find elements and extract values */
  var title = $('#listing-title-' + id).text().trim();
  var quantity_number = $('#quantity-needed-' + id).val();
  var quantity_description = $('#quantity-needed-' + id).val() + " " + $('#quantity-type-inside-' + id).text().trim();
  var expires_in = $('#expires-in-' + id).text().trim();
  var pickup_time = $('#pickup-time-' + id).text().trim();
  var location = $('#donor-location-' + id).text().trim();
  var volunteer_value = $('#pickup-volunteer-' + id + ' option:selected').val();
  if (volunteer_value) {
    var volunteer_name = $('#pickup-volunteer-' + id + ' option:selected').text();
  } else {
    var volunteer_name = "";
  };



  /* Fill popup for appearence */
  $('#popup-title').text(title);
  $('#popup-quantity-needed-value').text(quantity_description);
  $('#popup-expires-in-value').text(expires_in);
  $('#popup-pickup-time-value').text(pickup_time);
  $('#popup-location-value').text(location);
  $('#popup-volunteer-value').text(volunteer_name);

  /* Fill form with hidden elements  */
  $("#listing-confirm-form").append("<input class='dynamic-input-element-popup' type='hidden' name='listing_id' value='" + id + "'>");
  $("#listing-confirm-form").append("<input class='dynamic-input-element-popup' type='hidden' name='quantity' value='" + quantity_number + "'>");
  $("#listing-confirm-form").append("<input class='dynamic-input-element-popup' type='hidden' name='volunteer' value='" + volunteer_value + "'>");

});

/* On click update volunteer (in Accepted Listings) fill in the data in the update-volunteer-popup popup */
$('.update-volunteer-button').on('click', function () {
  var id = this.id.replace("update-volunteer-button-", "");

  /* Find elements and extract values */
  var volunteer_name = $('#pickup-volunteer-name-' + id).val();
  var volunteer_phone = $('#pickup-volunteer-phone-' + id).val();

  /* Fill popup for appearence */
  $('#popup-volunteer-name-value').text(volunteer_name);
  $('#popup-volunteer-phone-value').text(volunteer_phone);

  /* Fill form with hidden elements  */
  $("#update-volunteer-form").append("<input class='dynamic-input-element-popup' type='hidden' name='listing_offer_id' value='" + id + "'>");
  $("#update-volunteer-form").append("<input class='dynamic-input-element-popup' type='hidden' name='volunteer_name' value='" + volunteer_name + "'>");
  $("#update-volunteer-form").append("<input class='dynamic-input-element-popup' type='hidden' name='volunteer_phone' value='" + volunteer_phone + "'>");

});

//on dismiss, remove all dynamic input elements from popup
$('.modal').on('hide.bs.modal', function () {
  $('.dynamic-input-element-popup').remove();
})

/* On click delete offer (in Accepted Listings) fill the popup with hidden id field */
$('.delete-offer-button').on('click', function () {
  var id = this.id.replace("delete-offer-button-", "");
  /* Fill form with hidden elements  */
  $("#delete-offer-form").append("<input class='dynamic-input-element-popup' type='hidden' name='listing_offer_id' value='" + id + "'>");
});

//pass in the id of the listing caller
$('.add-volunteer-button').on('click', function () {
  var id = this.id.replace('add-volunteer-button-', '');
  $('#add-volunteer-form').append("<input type='hidden' id='popup-listing-id' class='dynamic-input-element-popup' name='listing_id' value='" + id + "'/>");
});

//submit the form to add volunteer with ajax, show errors if any
$("#add-volunteer-form").submit(function (e) {
  e.preventDefault(); // avoid to execute the actual submit of the form.
  var url = window.location.protocol + '//' + window.location.hostname + '/cso/active_listings/add_volunteer'; // Returns path only
  fd = new FormData(this);
  //activate loading animation
  $('#popup-loading').addClass('fa');
  $('#popup-loading').addClass('fa-spinner');
  $('#popup-loading').addClass('fa-pulse');
  $('#popup-loading').addClass('fa-2x');
  $('#popup-loading').addClass('fa-fw');
  $.ajax({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    url: url, // Url to which the request is send
    type: "POST", // Type of request to be send, called as method
    data: new FormData(this), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
    contentType: false, // The content type used when sending data to the server.
    cache: false, // To unable request pages to be cached
    processData: false, // To send DOMDocument or non processed data file it is set to false
    success: function (data) // A function to be called if request succeeds
    {
      //disable loading animation
      $('#popup-loading').removeClass('fa');
      $('#popup-loading').removeClass('fa-spinner');
      $('#popup-loading').removeClass('fa-pulse');
      $('#popup-loading').removeClass('fa-2x');
      $('#popup-loading').removeClass('fa-fw');
      if (data.errors) {

        if (data.errors['first_name']) {
          $('#first-name-form-group').addClass('has-error');
          $('#first-name-error').text(data.errors['first_name']);
        } else {
          $('#first-name-form-group').removeClass('has-error');
          $('#first-name-error').text('');
        }

        if (data.errors['last_name']) {
          $('#last-name-form-group').addClass('has-error');
          $('#last-name-error').text(data.errors['last_name']);
        } else {
          $('#last-name-form-group').removeClass('has-error');
          $('#last-name-error').text('');
        }

        if (data.errors['email']) {
          $('#email-form-group').addClass('has-error');
          $('#email-error').text(data.errors['email']);
        } else {
          $('#email-form-group').removeClass('has-error');
          $('#email-error').text('');
        }

        if (data.errors['phone']) {
          $('#phone-form-group').addClass('has-error');
          $('#phone-error').text(data.errors['phone']);
        } else {
          $('#phone-form-group').removeClass('has-error');
          $('#phone-error').text('');
        }

        if (data.errors['image']) {
          $('#image-form-group').addClass('has-error');
          $('#image-error').text(data.errors['image']);
        } else {
          $('#image-form-group').removeClass('has-error');
          $('#image-error').text('');
        }

      } else {
        //everything is fine
        var new_volunteer_id = data.id;
        //reset the popup
        $('#first-name-form-group').removeClass('has-error');
        $('#last-name-form-group').removeClass('has-error');
        $('#email-form-group').removeClass('has-error');
        $('#phone-form-group').removeClass('has-error');
        $('#image-form-group').removeClass('has-error');
        $('#first-name-error').text('');
        $('#last-name-error').text('');
        $('#email-error').text('');
        $('#phone-error').text('');
        $('#image-error').text('');
        $('#first_name').val('');
        $('#last_name').val('');
        $('#email').val('');
        $('#phone').val('');
        $('#image').val('');

        //retrieve the id
        var listing_id = $('#popup-listing-id').val();

        //retrieve the volunteers with ajax, and update every select element
        $.post('active_listings/get_volunteers', {
          'volunteer': this.value,
          '_token': $('meta[name="csrf-token"]').attr('content')
        }, function (data) {
          if (data) {

            //manage dropdown: remove all <option> elements, then fill them with the data from ajax
            $(".pickup-volunteer-name").each(function (index, obj) {
              $('#' + obj.id).children('option:not(:first)').remove();

              //append the other options retrieved from database
              $.each(data, function (key, value) {
                var obj_id = obj.id.replace('pickup-volunteer-', '');
                $('#' + obj.id).append('<option value=' + value.id + '>' + value.first_name + ' ' + value.last_name + '</option>');
                if ((value.id == new_volunteer_id) && (obj_id == listing_id)) {
                  $('#' + obj.id + ' option[value=' + value.id + ']').attr('selected', 'selected');
                  $('#pickup-volunteer-wrapper-' + listing_id).append('<div class="col-xs-12 help-block has-success" style="font-weight: bold;">Доставувачот е внесен успешно!</div>');
                  $('#listing-pickup-volunteer-' + listing_id).addClass('has-success');
                  $("#add-volunteer-button-" + listing_id).blur();
                }
              });
            });

            $('.pickup-volunteer-name').trigger("change");
          }
        });





        //dismiss the popup
        $('#add-volunteer-popup').modal('hide');

        //zatvori popup
        //resetiraj gi site input elementi vo popupopt
        //povlechi gi uste ednas volonterite so ajax i napolni go selectot za volonterite
        //ispishi status so zeleno deka e uspeshno vnesen nov volonter

      }
    }
  });

});

$(".donor-details").on('click', function () {
  var listing_id = this.id.replace("donor-details-", "");

  var first_name = $('#hidden-first-name-' + listing_id).text();
  var last_name = $('#hidden-last-name-' + listing_id).text();
  var organization = $('#hidden-organization-' + listing_id).text();
  var phone = $('#hidden-phone-' + listing_id).text();
  var address = $('#hidden-address-' + listing_id).text();

  $('#details-popup-first-name-value').text(first_name);
  $('#details-popup-last-name-value').text(last_name);
  $('#details-popup-organization-value').text(organization);
  $('#details-popup-phone-value').text(phone);
  $('#details-popup-address-value').text(address);

});
