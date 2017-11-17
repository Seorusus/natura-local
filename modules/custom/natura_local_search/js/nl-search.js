/**
 * Created by Admin on 17.10.2017.
 */
(function ($) {
  Drupal.behaviors.NLSearchBehavior = {
    attach: function (context, settings) {
      $(window).load(function () {
        var ind = 0;
        var tabInd = 0;
        var max = 0;
        var depth = 3;
        var keys = drupalSettings.nls.keys;
        drupalSettings.nls.resultIs = false;
        $('.nl-search-tab-content .view-nl-search').each(function () {
          for(i = 1; i <= depth; i++){
            if($(this).find('.row-counter-' + i + ' .title-wrapper h3 span').text() == keys){
              tabInd = ind;
              drupalSettings.nls.resultIs = true;
              return false;
            }
          }
          var hdr = $(this).find('.view-header').html();
          if(hdr.length) {
            var arr = $.trim(hdr).split(' ');
            if (max < parseInt(arr[0])) {
              max = parseInt(arr[0]);
              tabInd = ind;
              drupalSettings.nls.resultIs = true;
            }
          }
          ind++;
        });
        $("#stabs").tabs({active: tabInd});
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
