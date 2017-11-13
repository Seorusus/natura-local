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

      $(document).on("click", '.views-exposed-form .enable-values', function () {
        updateResults($(this));
      });
      $(document).on("click", '.reset-values', function () {
        $(this).parents('.views-exposed-form').eq(0).find("input[type=checkbox]").each(function() {
          $(this).removeAttr("checked");
        });
        updateResults($(this));
      });
      $(document).on("change", '.view-filters select.form-select', function () {
        updateResults($(this));
      });
      $(context).find('details summary').once('added-span').append('<span class="checked-count"></span>');
      $(context).find('span.checked-count').text(function () {
        var detail = $(this).parents('details').eq(0);
        var count = detail.find('input:checkbox:checked').length;
        if(count){
          return count;
        }
        return'';
      });
    }
  };

  function updateResults(obj){
    obj.parents('.views-exposed-form').eq(0).find('.form-submit').trigger('click');
  }
})(jQuery);
