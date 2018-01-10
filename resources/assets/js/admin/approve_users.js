
//approve cso - fill popup with cso_id
$('.approve-cso-button').on('click', function() {
    var cso_id = this.id.replace('approve-cso-button-','');
    $("#approve-cso-id").val(cso_id);
});

//approve donor - fill popup with cso_id
$('.approve-donor-button').on('click', function() {
    var donor_id = this.id.replace('approve-donor-button-','');
    $("#approve-donor-id").val(donor_id);
});

//reject cso - fill popup with cso_id
$('.reject-cso-button').on('click', function() {
    var cso_id = this.id.replace('reject-cso-button-','');
    $("#reject-cso-id").val(cso_id);
});

//reject donor - fill popup with cso_id
$('.reject-donor-button').on('click', function() {
    var donor_id = this.id.replace('reject-donor-button-','');
    $("#reject-donor-id").val(donor_id);
});
