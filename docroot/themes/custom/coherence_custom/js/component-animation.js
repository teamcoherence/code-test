(function ($) {
  'use strict';

  Drupal.behaviors.componentAnimation = {
    attach: function (context, settings) {
      var $window = $(window, context),
        fadeInFromPercentage = 0.8;

      $window.on('load resize scroll', addFadeInClass);

      function addFadeInClass() {
        var windowHeight = $window.height(),
          animateFrom = windowHeight * fadeInFromPercentage;

        $('.custom-fade-in', context)
          .each(function () {
            var $toFade = $(this),
              offsetTop = $toFade.offset().top,
              fromScroll = offsetTop - animateFrom;

            if ($window.scrollTop() >= fromScroll) {
              $toFade.addClass('faded-in');
            }
          });
      }
    }
  };
})(jQuery);