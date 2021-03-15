(function($) {
  'use strict';

  var initialised = false;

  Drupal.behaviors.header = {
    attach: function(context, settings) {
      // We only want to run this once, regardless of behaviour reattachment.
      if (initialised) {
        return;
      }
      initialised = true;

      // To allow the header to resize when the user has scrolled down the page

      windowScrollTop();
      var $window = $(window)
      $window.resize(windowScrollTop)
        .scroll(windowScrollTop);

      function windowScrollTop() {
        var $body = $('body');

        if($window.scrollTop() > 0) {
          $body.addClass('is-scrolled');
        }
        else {
          $body.removeClass('is-scrolled');
        }
      }
    }
  };

})(jQuery);