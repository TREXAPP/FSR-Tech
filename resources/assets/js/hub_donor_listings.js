// /*  If there is error in server validation, scroll to the listing with the error */
// $(document).ready(function () {
//     if (document.getElementById(window.location.hash.substring(1))) {
//         document.getElementById(window.location.hash.substring(1)).scrollIntoView();
//     }
// });

// /* On quantity-needed input field change:
//     - limit the value to the max_quantity
//     - auto update the beneficiaries number input field in regards with the portion size
//  */
// $('.quantity-needed-input').on('input', function () {

//     var id = this.id.replace('quantity-needed-', '');
//     var max_quantity = $('#quantity-offered-' + id).text().split(' ')[0];
//     var isnum = /^\d+$/.test(this.value);
//     if ((this.value != '') && (parseInt(this.value) > max_quantity || !$.isNumeric(this.value))) {
//         $(this).val(max_quantity);
//     }

//     console.log(this.value);

// });

// /* When a listing is accepted, fill in the data in a popup for appearence, and fill in the form with hidden fields for sending */
// $('.listing-submit-button').on('click', function () {
//     var id = this.id.replace("listing-submit-button-", "");

//     /* Find elements and extract values */
//     var title = $('#listing-title-' + id).text().trim();
//     var quantity_number = $('#quantity-needed-' + id).val();
//     var quantity_description = $('#quantity-needed-' + id).val() + " " + $('#quantity-type-inside-' + id).text().trim();
//     var expires_in = $('#expires-in-' + id).text().trim();
//     var pickup_time = $('#pickup-time-' + id).text().trim();

//     var donor_first_name = $('#hidden-first-name-' + id).text();
//     var donor_last_name = $('#hidden-last-name-' + id).text();
//     var donor_email = $('#hidden-email-' + id).text();
//     var donor_organization = $('#hidden-organization-' + id).text();
//     var donor_phone = $('#hidden-phone-' + id).text();
//     var donor_address = $('#hidden-address-' + id).text();
//     var donor_location = $('#hidden-location-' + id).text();

//     /* Fill popup for appearence */
//     $('#popup-title').text(title);
//     $('#popup-quantity-needed-value').text(quantity_description);
//     $('#popup-expires-in-value').text(expires_in);
//     $('#popup-pickup-time-value').text(pickup_time);

//     $('#popup-donor-first-name-value').text(donor_first_name);
//     $('#popup-donor-last-name-value').text(donor_last_name);
//     $('#popup-donor-email-value').text(donor_email);
//     $('#popup-donor-organization-value').text(donor_organization);
//     $('#popup-donor-phone-value').text(donor_phone);
//     $('#popup-donor-address-value').text(donor_address);
//     $('#popup-donor-location-value').text(donor_location);

//     /* Fill form with hidden elements  */
//     $("#listing-confirm-form").append("<input class='dynamic-input-element-popup' type='hidden' name='listing_id' value='" + id + "'>");
//     $("#listing-confirm-form").append("<input class='dynamic-input-element-popup' type='hidden' name='quantity' value='" + quantity_number + "'>");

// });

// //on dismiss, remove all dynamic input elements from popup
// $('.modal').on('hide.bs.modal', function () {
//     $('.dynamic-input-element-popup').remove();
// })

// /* On click delete offer (in Accepted Listings) fill the popup with hidden id field */
// $('.delete-offer-button').on('click', function () {
//     var id = this.id.replace("delete-offer-button-", "");
//     /* Fill form with hidden elements  */
//     $("#delete-offer-form").append("<input class='dynamic-input-element-popup' type='hidden' name='hub_listing_offer_id' value='" + id + "'>");
// });

// $(".donor-details").on('click', function () {
//     var listing_id = this.id.replace("donor-details-", "");

//     var first_name = $('#hidden-first-name-' + listing_id).text();
//     var last_name = $('#hidden-last-name-' + listing_id).text();
//     var organization = $('#hidden-organization-' + listing_id).text();
//     var phone = $('#hidden-phone-' + listing_id).text();
//     var address = $('#hidden-address-' + listing_id).text();
//     var location = $('#hidden-location-' + listing_id).text();

//     $('#donor-details-popup-first-name-value').text(first_name);
//     $('#donor-details-popup-last-name-value').text(last_name);
//     $('#donor-details-popup-organization-value').text(organization);
//     $('#donor-details-popup-phone-value').text(phone);
//     $('#donor-details-popup-address-value').text(address);
//     $('#donor-details-popup-location-value').text(location);

// });

// /*checkbox */
// $('.checkbox-reposted').on('click', function () {
//     var id = this.id.replace("checkbox-reposted-", "");
//     if ($(this).is(":checked")) {
//         $('#reposted-controls-' + id).show();
//         $('#listing-submit-button-' + id).text("Прифати и Објави");
//     } else {
//         $('#reposted-controls-' + id).hide();
//         $('#listing-submit-button-' + id).text("Прифати");
//     }

// })
