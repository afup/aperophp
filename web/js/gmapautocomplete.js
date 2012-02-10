// http://code.google.com/intl/fr/apis/maps/documentation/javascript/places.html#places_autocomplete
var input = document.getElementById('drink_placegmap');
autocomplete = new google.maps.places.Autocomplete(input);
google.maps.event.addListener(autocomplete, 'place_changed', function() {
  var place = autocomplete.getPlace();
  $('#drink_place').val(place.name);
  $('#drink_map').val(place.formatted_address);
  $('#drink_place_disabled').val(place.name);
  $('#drink_map_disabled').val(place.formatted_address);
});