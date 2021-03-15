(function ($) {
  'use strict';

  Drupal.behaviors.customScrollTo = {
    attach: function (context, settings) {

      $('.scroll-to-link').on('click', function (e) {
        e.preventDefault();
        var target = $(this).attr('href');
        if (target !== undefined && target.length) {
          var $header = $('#page-wrapper').find('> header'),
            fixedMenuOffset = $header.css('position') == 'fixed' ? $header.height() : 0;

          $('html, body').stop().animate({
            scrollTop: $(target).offset().top - fixedMenuOffset
          }, 500);
        }
      });
    }
  }

})(jQuery);