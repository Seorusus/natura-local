(function ($, Drupal, drupalSettings) {
  'use strict';

  Drupal.behaviors.foundation = {
    attach: function (context, settings) {
      /**
       * When page is loaded
       */
      $('body').addClass('page-animate');

      /**
       * Locations block - display image as background image
       */
        $('.block-views-blocklocations-block-1 .views-row').each(function() {
            var src = $(this).find('.views-field-field-image img').attr('src');

            $(this).find('.views-field-field-image').css({
                'background-image': 'url(' + src + ')'
            });
        });

      /**
       * Toggle responsive navigation
       */
      $('.navigation-toggler').on('click', function() {
          $('#header .block.block-menu.navigation').toggleClass('open');
      });

      /**
       * Listing gallery
       */
      $('.field--name-field-gallery').owlCarousel({
        items: 1,
        nav: true,
        navText: ['<i class="fa fa-chevron-left"></i>', '<i class="fa fa-chevron-right"></i>']
      });

      /**
       * Hero search fields
       */
      $('#block-exposedformrecent-listingspage-1 label').on('click', function() {
        $(this).addClass('hide').closest('div');
      });

      $('#block-exposedformrecent-listingspage-1 input').on('focusout', function() {                        
        if ($(this).val().length === 0) {
          $(this).closest('div').find('label').removeClass('hide');
        }        
      });

      /**
      * Smooth scroll to top
      */
      $('#to-top').on('click', function(e) {
        e.preventDefault();

        $('html,body').animate({
          scrollTop: 0
        }, 1200);
      });
    }
  }; 
})(jQuery, Drupal, drupalSettings);

/**
 * Listing detail Google Map
 */
function initMap() {
  var el = jQuery('#node-listing-map');

  if (el.length != 0) {
    var position = new google.maps.LatLng(el.data('latitude'), el.data('longitude'));
    var mapOptions = {
      zoom: 13,
      scrollwheel: false,
      center: position,
      disableDefaultUI: true,
      styles: [{"featureType":"water","elementType":"geometry","stylers":[{"color":"#e9e9e9"},{"lightness":17}]},{"featureType":"landscape","elementType":"geometry","stylers":[{"color":"#f5f5f5"},{"lightness":20}]},{"featureType":"road.highway","elementType":"geometry.fill","stylers":[{"color":"#ffffff"},{"lightness":17}]},{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"color":"#ffffff"},{"lightness":29},{"weight":0.2}]},{"featureType":"road.arterial","elementType":"geometry","stylers":[{"color":"#ffffff"},{"lightness":18}]},{"featureType":"road.local","elementType":"geometry","stylers":[{"color":"#ffffff"},{"lightness":16}]},{"featureType":"poi","elementType":"geometry","stylers":[{"color":"#f5f5f5"},{"lightness":21}]},{"featureType":"poi.park","elementType":"geometry","stylers":[{"color":"#dedede"},{"lightness":21}]},{"elementType":"labels.text.stroke","stylers":[{"visibility":"on"},{"color":"#ffffff"},{"lightness":16}]},{"elementType":"labels.text.fill","stylers":[{"saturation":36},{"color":"#333333"},{"lightness":40}]},{"elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"transit","elementType":"geometry","stylers":[{"color":"#f2f2f2"},{"lightness":19}]},{"featureType":"administrative","elementType":"geometry.fill","stylers":[{"color":"#fefefe"},{"lightness":20}]},{"featureType":"administrative","elementType":"geometry.stroke","stylers":[{"color":"#fefefe"},{"lightness":17},{"weight":1.2}]}]
    };

    var map = new google.maps.Map(document.getElementById('node-listing-map'), mapOptions);       
    var image = {
      size: new google.maps.Size(48, 48),
      url: 'https://d30y9cdsu7xlg0.cloudfront.net/noun-svg/608842.svg?Expires=1475418945&Signature=NNJRB1m0w5mIRJQ4ygeLHCl3vDqZ6s3ce-BZTR41xr3yePsTTW1-XUTCrfSM35Satfm5QeTpqjmJXNzEhJYtf~GqnPWApvr7425-l7Q~rB7z5LufJZ-RmhjwSdaon8s4w20rQI0FszKnJn57KQH41nNCjRT8C7oeXiGBGwHOnJ4_&Key-Pair-Id=APKAI5ZVHAXN65CHVU2Q'
    };

    var marker = new google.maps.Marker({
        position: position,
        icon: el.data('icon'),
        map: map
    });
  }
}