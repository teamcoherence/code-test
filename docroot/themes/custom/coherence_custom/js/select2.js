(function($) {
  Drupal.behaviors.cohSelect2 = {
    attach: function(context, settings) {
      $('select', context).once('coh-select2').each(function() {
        $(this).select2();
      });
    }
  }
})(jQuery);