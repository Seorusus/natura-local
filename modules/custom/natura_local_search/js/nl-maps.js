/**
 * @file
 * Attaches behaviors for the custom Google Maps.
 */

(function ($, Drupal) {

  /**
   * Initializes the map.
   */
  function init (geofield, title) {
    //console.log(geofield);
    var point = {lat: geofield.lat, lng: geofield.lon};

    var map = new google.maps.Map(document.getElementById('map_canvas'), {
      center: point,
      scrollwheel: true,
      zoom: 10
    });

    var infowindow = new google.maps.InfoWindow({
      content: title
    });

    var marker = new google.maps.Marker({
      position: point,
      map: map,
      title: title
    });
    marker.addListener('click', function() {
      infowindow.open(map, marker);
    });
  }

  function gmapInit() {
    var myLatlng = new google.maps.LatLng(41.536411, 1.382184);
    var myOptions = {
      zoom: 8,
      center: myLatlng,
      mapTypeId: google.maps.MapTypeId.ROADMAP
    }
    var map = new google.maps.Map(document.getElementById("map-canvas"), myOptions);
    drupalSettings.currmap = map;
    drupalSettings.currmarkers = [];
  }

  function setFunction(){
    alert('this happens one time');
  }

  $(document).on("click", '#stabs li',function () {
    loadMarkers($(this));
  });

  function loadMarkers(obj) {
    if(!drupalSettings.nls.resultIs){
      //loadGooglePoint();
      return false;
    }
    var dest = $('#'+obj.attr('aria-controls'));
    drupalSettings.currmarkers.every(function (val, key, arr) {
      val.setMap(null);
    });
    var markers  = [];
    bounds = new google.maps.LatLngBounds();
    dest.find('.marker-location .geolocation-latlng').each(function () {
      var parent = $(this).parents('.views-row').eq(0);
      var detail = parent.find('.marker-preview').html();
      var nid = parent.find('.node-id span').text();
      var type = parent.find('.node-type span').text();
      var currLoc = $(this).text().split(',');
      var point = new google.maps.LatLng(currLoc[0], currLoc[1]);
      var image = {
        url: drupalSettings.nls.mark_imgs[type],
        size: new google.maps.Size(43, 57),
        origin: new google.maps.Point(0, 0),
        anchor: new google.maps.Point(17, 34),
        scaledSize: new google.maps.Size(30, 40)
      };
      var marker = new google.maps.Marker({
        position: point,
        map: drupalSettings.currmap,
        icon: image,
      });
      marker.infowindow = new google.maps.InfoWindow({
        content: detail
      });
      marker.nid = nid;
      google.maps.event.addListener(marker, 'mouseover', function() {
        marker.infowindow.open(drupalSettings.currmap, marker);
      });
      google.maps.event.addListener(marker, 'mouseout', function() {
        setTimeout(function() {
          marker.infowindow.close();
        }, 1000);
      });
      markers[nid] = marker;
      bounds.extend(point);
    });
    if(markers.length){
      drupalSettings.currmap.fitBounds(bounds);
      google.maps.event.addListener(drupalSettings.currmap, 'bounds_changed', function() {
        setTimeout(function() {
          setVisibleNodes();
        }, 700);
      });
    } else if(drupalSettings.currmarkers.length) {
      gmapInit();
    }
    drupalSettings.currmarkers = markers;
  }

  function loadGooglePoint() {
    var geocoder = new google.maps.Geocoder();
    geocoder.geocode({address: addressInput}, function(results, status) {

      if (status == google.maps.GeocoderStatus.OK) {

        map.setCenter(results[0].geometry.location);

        map.setZoom(17);
      }
    });
    return;
  }

  function setVisibleNodes(){
    var map = drupalSettings.currmap;
    var markers = drupalSettings.currmarkers;
    var bounds = map.getBounds();
    var a=1;
    var currTab = $('#' + $('#stabs li.ui-tabs-active').attr('aria-controls'));
    currTab.find('.views-row').each(function () {
      $(this).hide();
    });
    markers.forEach(function (marker, nid, arr) {
      if( bounds.contains(marker.getPosition()) ){
        currTab.find('.views-row.row-node-' + marker.nid).show();
      }
    });
  }

  function settings_ajax_load() {
    $.ajax({
      url: '/search-events',
      dataType: 'json',
      method: 'post',
      success: function (data) {
        drupalSettings.something = data[0].settings.something;
      }
    });
    // $('#map-container').load("/tabs/ajax/map-container/piois_etc/page_1/59+60+62");
  }

  $(document).ajaxComplete(function(event, xhr, settings) {
    if (settings.url.indexOf('views/ajax')+1){
      //settings_ajax_load();
      loadMarkers($('#stabs li.ui-tabs-active'));
    }
    //settings_ajax_load();
    if (drupalSettings.something) {
      //$('#map-canvas').once('was-set', setFunction());
    }
  });
  function toogleAnimation(obj, set){
    var nid = obj.find('.node-id span').text();
    if (nid in drupalSettings.currmarkers){
      var marker = drupalSettings.currmarkers[nid];
      if (set == 'off') {
        marker.setAnimation(null);
      } else {
        marker.setAnimation(google.maps.Animation.BOUNCE);
      }

    }
  }
  $(document).on({
    mouseenter: function(){
      toogleAnimation($(this), 'on');
    },
    mouseleave: function(){
      toogleAnimation($(this), 'off');
    }
  }, '#search-container .views-row');

  Drupal.behaviors.customMapBehavior = {
    attach: function (context, settings) {
      //init(settings.geofield, settings.title);
      gmapInit();
      $(window).load(function () {
        loadMarkers($('#stabs li.ui-tabs-active'));
      });
    }
  };

})(jQuery, Drupal);