(function($) {
    'use strict';

    Drupal.behaviors.socialSharing = {
        attach: function(context, settings) {

            var socialShareContainer = $('.js-social-share-container', context);

            $('.js-social-share-toggle', context).on('click', function(e){
               e.preventDefault();
               $(this).toggleClass('is-active');
               socialShareContainer.toggleClass('is-open');
            });

          // Social share links

          var url = encodeURIComponent(location.href);

          // Twitter

          $('.js-twitter-link').on('click', function(e){

            e.preventDefault();

            window.open('https://twitter.com/intent/tweet?text=' + url, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');

          });

          // Facebook

          $('.js-facebook-link').on('click', function(e){

            e.preventDefault();

            window.open('https://www.facebook.com/sharer/sharer.php?u=' + url, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=981');

          });

          // Linkedin

          $('.js-linkedin-link').on('click', function(e){

            e.preventDefault();

            var metaTitle = '';

            if (document.head.querySelector('meta[name=title]') !== null) {
              var metaTitle = encodeURIComponent(document.head.querySelector('meta[name=title]').content);
            }

            var metaDescription = '';

            if (document.head.querySelector('meta[name=description]') !== null) {
              var metaDescription = encodeURIComponent(document.head.querySelector('meta[name=description]').content);
            }

            window.open('https://www.linkedin.com/shareArticle?mini=true&url=' + url + '&title=' + metaTitle + '&summary=' + metaDescription, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');

          });

          // Google plus

          $('.js-google-plus-link').on('click', function(e){

            e.preventDefault();

            window.open('https://plus.google.com/share?url=' + url, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');

          });

          // Email

          $('.js-email-share-link').on('click', function(e){

            e.preventDefault();

            location.href = 'mailto:?subject=' + url + '&body=' + url;

          });

        }
    };
})(jQuery);
