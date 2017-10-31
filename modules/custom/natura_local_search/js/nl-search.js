/**
 * Created by Admin on 17.10.2017.
 */
(function ($) {
  Drupal.behaviors.NLSearchBehavior = {
    attach: function (context, settings) {
      $(window).load(function () {
        $("#stabs").tabs();
      });

      $(document).on("click", '#stabs',function () {
        $.ajax({
          url: '/search-destins-get-map',
          dataType: 'html',
          type: 'GET',
          cache: false,
          success: function (response, textStatus, xhr) {
            $('#map-container').html(response.data);
          }
        });
      });
    }
  };
})(jQuery);
