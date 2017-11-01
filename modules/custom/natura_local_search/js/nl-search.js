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
  // $(document).on("click", '#stabs',function () {
  //   project_ajax_load();
  // });
  function project_ajax_load() {
    $.ajax({
      url: '/tabs/ajax/map-container/piois_etc/page_1/59+60+62',
      dataType: 'json',
      success: function (data) {
          $('#map-container').html(data[3].data);
        $('#map-container').append(data[2].data);
      }
    });
   // $('#map-container').load("/tabs/ajax/map-container/piois_etc/page_1/59+60+62");
  }
})(jQuery);
