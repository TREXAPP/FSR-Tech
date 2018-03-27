$('#add-new-quantity-type').on('click', function() {

  var el = $('div[id^="admin-quantity-type-entry"]:last');
  var num = el[0].id.replace('admin-quantity-type-entry-','');
  var new_num = parseInt(num) + 1;
  var clone = el.clone().prop('id', 'admin-quantity-type-entry-' + new_num);
  clone.find('select').attr('name', 'quantity_type_' + new_num);
  clone.find('input[type=number]').attr('name', 'portion_size_' + new_num);
  clone.find('input[type=number]').val("");
  clone.find('input[type=radio]').removeAttr('checked');
  clone.find('input[type=radio]').val(new_num);
  clone.appendTo('.admin-quantity-type-wrapper');

  $('#number-of-quantity-types').val(new_num);

  if (new_num > 1) {
    $('#remove-quantity-type').show();
  }

});

$('#remove-quantity-type').on('click', function() {
  var el = $('div[id^="admin-quantity-type-entry"]:last');
  var num = el[0].id.replace('admin-quantity-type-entry-','');

  $('.admin-quantity-type-entry').last().remove();

  $('#number-of-quantity-types').val(num-1);

  if (num <= 2) {
    $('#remove-quantity-type').hide();
  }
});
