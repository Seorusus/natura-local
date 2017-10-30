/**
 * Created by Admin on 17.10.2017.
 */
(function ($) {
  Drupal.behaviors.NLSearchBehavior = {
    attach: function (context, settings) {
      $(window).load(function () {
        $("#stabs").tabs();
      });
    }
  };
})(jQuery);
