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
$('.hub-quantity-needed-input').on('input', function () {

    var id = this.id.replace('hub-quantity-needed-', '');
    var max_quantity = parseInt($('#quantity-offered-' + id).text().split(' ')[0].trim());

    if ((this.value !== '') && (!$.isNumeric(this.value) || parseInt(this.value) > max_quantity)) {
        $(this).val(max_quantity);
    }
    var repostedValue = this.value;
    if (!$.isNumeric(this.value)) {
        repostedValue = 0;
    }
    var quantityRepostedInput = $('#quantity-reposted-' + id);
    quantityRepostedInput.val(repostedValue);
    quantityRepostedInput.attr('max', repostedValue);

});

$('.hub-quantity-reposted-input').on('input', function () {
    var id = this.id.replace('quantity-reposted-', '');
    max_quantity = parseInt($('#hub-quantity-needed-' + id).val());
    if ((this.value !== '') && (!$.isNumeric(this.value) || parseInt(this.value) > max_quantity)) {
        $(this).val(max_quantity);
    }
});

/* When a listing is accepted, fill in the data in a popup for appearence, and fill in the form with hidden fields for sending */
$('.hub-listing-submit-button').on('click', function () {
    var id = this.id.replace("hub-listing-submit-button-", "");
    var reposting = false;
    var checkbox_reposted = $('#checkbox-reposted-' + id);
    if (checkbox_reposted.is(":checked")) {
        reposting = true;
    }

    /* Find elements and extract values */
    var title = $('#listing-title-' + id).text().trim();
    var quantity_number = $('#hub-quantity-needed-' + id).val();
    var quantity_description = $('#hub-quantity-needed-' + id).val() + " " + $('#quantity-type-inside-' + id).text().trim();
    var expires_in = $('#expires-in-' + id).text().trim();
    var pickup_time = $('#pickup-time-' + id).text().trim();

    var quantity_reposted = $('#quantity-reposted-' + id);
    var quantity_type_reposted = $('#quantity-type-inside-' + id);
    var expires_in_reposted = $('#expires_in-reposted-' + id);
    var time_type_reposted = $('#time_type-reposted-' + id + ' option:selected');
    var description_reposted = $('#description-reposted-' + id);

    var product_id = $('#hidden-product-id-' + id).text().trim();
    var food_type = $('#hidden-food-type-' + id).text().trim();
    var quantity_type_id = $('#hidden-quantity-type-id-' + id).text().trim();
    var sell_by_date = $('#hidden-sell-by-date-' + id).text().trim();

    var donor_first_name = $('#hidden-first-name-' + id).text();
    var donor_last_name = $('#hidden-last-name-' + id).text();
    var donor_email = $('#hidden-email-' + id).text();
    var donor_organization = $('#hidden-organization-' + id).text();
    var donor_phone = $('#hidden-phone-' + id).text();
    var donor_address = $('#hidden-address-' + id).text();
    var donor_location = $('#hidden-location-' + id).text();

    /* Validate */
    if (reposting && !validate(id, quantity_reposted, expires_in_reposted)) {
        return;
    }

    /* Fill popup for appearence */
    $('#popup-title').text(title);
    $('#popup-hub-quantity-needed-value').text(quantity_description);
    $('#popup-expires-in-value').text(expires_in);
    $('#popup-pickup-time-value').text(pickup_time);
    $('#popup-donor-location-value').text(donor_location);

    if (reposting) {
        $('#donor-info-modal').hide();
        $('#reposted-info-modal').show();

        $('#popup-reposted-product-value').text(title);
        $('#popup-reposted-quantity-value').text(quantity_reposted.val() + ' ' + quantity_type_reposted.text());
        $('#popup-reposted-available-value').text(expires_in_reposted.val() + ' ' + time_type_reposted.text());
        $('#popup-reposted-description-value').text(description_reposted.val());
    } else {
        $('#donor-info-modal').show();
        $('#reposted-info-modal').hide();

        $('#popup-donor-first-name-value').text(donor_first_name);
        $('#popup-donor-last-name-value').text(donor_last_name);
        $('#popup-donor-email-value').text(donor_email);
        $('#popup-donor-organization-value').text(donor_organization);
        $('#popup-donor-phone-value').text(donor_phone);
        $('#popup-donor-address-value').text(donor_address);
    }

    /* Fill form with hidden elements  */
    $("#listing-confirm-form").append("<input class='dynamic-input-element-popup' type='hidden' name='listing_id' value='" + id + "'>");
    $("#listing-confirm-form").append("<input class='dynamic-input-element-popup' type='hidden' name='quantity' value='" + quantity_number + "'>");

    $("#listing-confirm-form").append("<input class='dynamic-input-element-popup' type='hidden' name='checkbox_reposted' value='" + reposting + "'>");
    $("#listing-confirm-form").append("<input class='dynamic-input-element-popup' type='hidden' name='product_id' value='" + product_id + "'>");
    $("#listing-confirm-form").append("<input class='dynamic-input-element-popup' type='hidden' name='food_type' value='" + food_type + "'>");
    $("#listing-confirm-form").append("<input class='dynamic-input-element-popup' type='hidden' name='quantity_type_id' value='" + quantity_type_id + "'>");
    $("#listing-confirm-form").append("<input class='dynamic-input-element-popup' type='hidden' name='sell_by_date' value='" + sell_by_date + "'>");
    $("#listing-confirm-form").append("<input class='dynamic-input-element-popup' type='hidden' name='description_reposted' value='" + description_reposted.val() + "'>");
    $("#listing-confirm-form").append("<input class='dynamic-input-element-popup' type='hidden' name='quantity_reposted' value='" + quantity_reposted.val() + "'>");
    $("#listing-confirm-form").append("<input class='dynamic-input-element-popup' type='hidden' name='expires_in_reposted' value='" + expires_in_reposted.val() + "'>");
    $("#listing-confirm-form").append("<input class='dynamic-input-element-popup' type='hidden' name='time_type_reposted' value='" + time_type_reposted.val() + "'>");

    $('#confirm-listing-popup').modal('show');

});

var validate = function (id, quantity_reposted, expires_in_reposted) {
    var valid = true;
    var quantity_reposted_wrapper = $('#quantity-reposted-wrapper-' + id);
    if (!quantity_reposted.val() || !$.isNumeric(quantity_reposted.val()) || !(quantity_reposted.val() > 0)) {
        valid = false;
        quantity_reposted_wrapper.addClass('has-error');
    } else {
        quantity_reposted_wrapper.removeClass('has-error');
    }

    var expires_in_wrapper = $('#expires_in-reposted-wrapper-' + id);
    if (!expires_in_reposted.val() || !$.isNumeric(expires_in_reposted.val()) || !(expires_in_reposted.val() > 0)) {
        valid = false;
        expires_in_wrapper.addClass('has-error');
    } else {
        expires_in_wrapper.removeClass('has-error');
    }

    if (!valid) {
        return false;
    }
    return true;
};

//on dismiss, remove all dynamic input elements from popup
$('.modal').on('hide.bs.modal', function () {
    $('.dynamic-input-element-popup').remove();
})

/* On click delete offer (in Accepted Listings) fill the popup with hidden id field */
$('.delete-offer-button').on('click', function () {
    var id = this.id.replace("delete-offer-button-", "");
    /* Fill form with hidden elements  */
    $("#delete-offer-form").append("<input class='dynamic-input-element-popup' type='hidden' name='hub_listing_offer_id' value='" + id + "'>");
});

$(".donor-details").on('click', function () {
    var listing_id = this.id.replace("donor-details-", "");

    var first_name = $('#hidden-first-name-' + listing_id).text();
    var last_name = $('#hidden-last-name-' + listing_id).text();
    var organization = $('#hidden-organization-' + listing_id).text();
    var phone = $('#hidden-phone-' + listing_id).text();
    var address = $('#hidden-address-' + listing_id).text();
    var location = $('#hidden-location-' + listing_id).text();

    $('#donor-details-popup-first-name-value').text(first_name);
    $('#donor-details-popup-last-name-value').text(last_name);
    $('#donor-details-popup-organization-value').text(organization);
    $('#donor-details-popup-phone-value').text(phone);
    $('#donor-details-popup-address-value').text(address);
    $('#donor-details-popup-location-value').text(location);

});

/*checkbox */
$('.checkbox-reposted').on('click', function () {
    var id = this.id.replace("checkbox-reposted-", "");
    if ($(this).is(":checked")) {
        $('#reposted-controls-' + id).show();
        $('#hub-listing-submit-button-' + id).text("Прифати и Објави");
    } else {
        $('#reposted-controls-' + id).hide();
        $('#hub-listing-submit-button-' + id).text("Прифати");
    }

})
