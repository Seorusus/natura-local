/**
 * Created by Admin on 17.10.2017.
 */
(function ($) {
  Drupal.behaviors.NLSFormBehavior = {
    attach: function (context, settings) {
      $(document).on("click", 'ul.ui-autocomplete li',function () {
        var termUrl = $(this).find('span.invisible-span').text();
        if(termUrl){
          window.location = termUrl;
          return false;
        }
      });

      $(document).on("click", '#views-exposed-form-nl-search-page-2 .enable-values', function () {
        updateResults();
      });
      $(document).on("click", '.reset-values', function () {
        $(context).find("#views-exposed-form-nl-search-page-2 input[type=checkbox]").each(function() {
          $(this).removeAttr("checked");
        });
        updateResults();
      });
      $(context).find('details summary').once('added-span').append('<span class="checked-count"></span>');
      $(context).find('span.checked-count').text(function () {
        var detail = $(this).parents('details').eq(0);
        //alert(detail.hasClass('form-item'));
        var count = detail.find('input:checkbox:checked').length;
        if(count){
          return count;
        }
        return'';
      });
    }
  };

  function updateResults(){
    $("#views-exposed-form-nl-search-page-2 .form-submit").trigger('click');
  }
})(jQuery);
