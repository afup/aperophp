var geocoder;
var map;

function initialize(identifier) {
  var latlng = new google.maps.LatLng(-34.397, 150.644);
  var myOptions = {
    zoom: 15,
    center: latlng,
    mapTypeControl: false,
    mapTypeId: google.maps.MapTypeId.ROADMAP
  };
  map = new google.maps.Map(document.getElementById(identifier), myOptions);
  geocoder = new google.maps.Geocoder();
}

function codeAddress(address) {
  geocoder.geocode( {'address': address}, function(results, status) {
    if (status == google.maps.GeocoderStatus.OK) {
      map.setCenter(results[0].geometry.location);
      var marker = new google.maps.Marker({
          map: map,
          position: results[0].geometry.location
      });
    } else {
      //alert("Impossible de trouver l'adresse sur la carte : " + status);
    }
  });
}