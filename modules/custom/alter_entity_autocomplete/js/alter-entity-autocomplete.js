/**
 * Created by Admin on 17.10.2017.
 */
(function ($) {
  Drupal.behaviors.alterEntityAutocompleteBehavior = {
    attach: function (context, settings) {
      $(document).on("click", 'ul.ui-autocomplete li',function () {
        var termUrl = $(this).find('span.invisible-span').text();
        if(termUrl){
          window.location = termUrl;
          return false;
        }
      });
    }
  };
})(jQuery);