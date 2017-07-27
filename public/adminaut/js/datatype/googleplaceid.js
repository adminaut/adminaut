function placeMarker($element, location) {
    var marker = $element.data('marker');
    if (marker == null) {
        marker = new google.maps.Marker({
            position: location,
            map: $element.data('map')
        });
        $element.data('marker', marker);
    } else {
        marker.setPosition(location);
    }
}

(function ($) {
    appendScript('https://maps.googleapis.com/maps/api/js?libraries=places&key=' + google_api);

    $(window).load(function () {
        $('.datatype-googleplaceid').each(function () {
            var $this = $(this);
            var identifier = $this.attr('id');
            var $input = $('#' + identifier + '-input').data('identifier', identifier);
            var $mapDiv = $('#' + identifier + '-map').data('identifier', identifier);
            var $searchInput = $('#' + identifier + '-search-input').data('identifier', identifier);
            var useGeoLocation = true;
            var showMarker = false;
            var latitude = 0;
            var longitude = 0;
            var centerLocation = new google.maps.LatLng(0, 0);
            var placeId = "";

            if($input.val()) {
                placeId = $input.val();
                showMarker = true;
                useGeoLocation = false;
            }

            if(useGeoLocation && navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    centerLocation = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
                }, function() {
                    console.log("Your browser not support Geolocation");
                });
            } else if(useGeoLocation) {
                console.log("Your browser not support Geolocation");
            }

            var map = new google.maps.Map($mapDiv[0], {
                zoom: 4,
                center: centerLocation,
                streetViewControl: false
            });

            var placeidService = new google.maps.places.PlacesService(map);
            if(placeId.length) {
                var request = {
                    placeId: placeId
                };

                placeidService.getDetails(request, function (place, status) {
                    if (status === google.maps.places.PlacesServiceStatus.OK) {
                        placeMarker($this, place.geometry.location);
                        map.setCenter(place.geometry.location);
                        map.setZoom(16);
                    }
                });
            }

            var autocomplete = new google.maps.places.Autocomplete($searchInput[0]);
            autocomplete.bindTo('bounds', map);
            map.controls[google.maps.ControlPosition.TOP_LEFT].push($searchInput[0]);


            $this.data('map', map);
            $this.data('placeidservice', placeidService);
            $this.data('autocomplete', autocomplete);

            autocomplete.addListener('place_changed', function() {
                var place = autocomplete.getPlace();
                if (!place.geometry) {
                    return;
                }

                if (place.geometry.viewport) {
                    map.fitBounds(place.geometry.viewport);
                } else {
                    map.setCenter(place.geometry.location);
                    map.setZoom(18);
                }

                placeMarker($this, place.geometry.location);
                $input.val(place.place_id);
            });

            map.addListener('click', function(event) {
                // if(event.placeId) {
                    placeMarker($this, event.latLng);
                    $this.data('map').setCenter(place.geometry.location);
                    $this.data('map').setZoom(18);
                    $input.val(event.placeId);
                // }
            });
        });
    });
})(jQuery);