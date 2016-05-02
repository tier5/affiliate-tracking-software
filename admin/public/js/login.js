//Interactive Chart
jQuery(window).ready(function ($) {
  var month = $('#expirationval-m').val();
  var year = $('#expirationval-y').val();
  if (month != '') {
    $('.expiry').val(month + ' / ' + year);
    $("input[name='expiry-month']").val(month);
    $("input[name='expiry-year']").val(year);
    //console.log('month:' + $('.expiry').val() + ':year:' + $("input[name='expiry-year']").val());
  }
});