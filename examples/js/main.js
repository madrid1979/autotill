$(function() {
  $('#calcChange').on('click', function() {
    calcmychange();
  });
  $('.mode').on('click', function() {
    console.log($(this).attr('id'));
    calcmychange($(this).attr('id'));
  });

  function calcmychange(mode = 'standard') {
    //clear data
    $('#changeTotal').html('');
    $('#results').html('');

    // Get field values
    p1 = ($('#price').val()) ? $('#price').val().replace(/[$,]/g, '') : 0;
    p2 = ($('#paid').val()) ? $('#paid').val().replace(/[$,]/g, '') : 0;

    // Do basic math
    total = (p2 - p1).toFixed(2);
    $('#changeTotal').html('Change Due: $' + total);

    $.getJSON('../autotill/api/?val=' + total + '&mode=' + mode, function(data) {
      console.log(data);
      change = [];
      $.each(data, function(k, v) {
        k = (k.match(/((0.1)|(0.5))/gm) != null) ? k + '0' : k;
        ctype = (k >= 1) ? '<i class="far fa-money-bill-wave"></i>' : '<i class="far fa-coin"></i>';
        change.push('<li class="dnoms__' + k.replace('.', '-') + '">' + ctype + ' $' + k + ' : ' + v + '</li>');
      });
      $('#results').append(change);
    });
  }
});