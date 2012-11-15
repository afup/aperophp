// http://code.google.com/intl/fr/apis/maps/documentation/javascript/places.html#places_autocomplete
var memoryPlace = '';
$(function() {
	var input = document.getElementById('drink_placegmap');
	autocomplete = new google.maps.places.Autocomplete(input);
	google.maps.event.addListener(autocomplete, 'place_changed', function() {
		var place = autocomplete.getPlace();
		$('#drink_place').val(place.name);
		$('#drink_address').val(place.formatted_address);
		$('#drink_latitude').val(place.geometry.location.lat().toFixed(5));
		$('#drink_longitude').val(place.geometry.location.lng().toFixed(5));
		$('#drink_place_disabled').val(place.name);
		$('#drink_address_disabled').val(place.formatted_address);
		memoryPlace = $("#drink_placegmap").val();
	});
    // Autoriser un RETURN ou le blur dans l'input avec l'autocomplete est déstabilisant
	$("#drink_placegmap").keypress(function(e){
		if (e.keyCode == 13) {
    		return false;
		}
	});

});
function checkMaps() {
    $("#drink_placegmap").val(memoryPlace);
}
