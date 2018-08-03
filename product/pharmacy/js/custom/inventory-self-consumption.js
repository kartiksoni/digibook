$( document ).ready(function() {
  $('body').on('click', '.btn-addmore-product', function() {
        var html = $('#copy-html').html();
        $('#self-more').append(html);
  });

  $('body').on('click', '.btn-remove-product', function(e) {
        e.preventDefault();
        $(this).closest ('.self-sub-more').remove ();
    });
      
});
