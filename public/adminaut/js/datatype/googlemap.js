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
        $('.datatype-map').each(function () {
            var $this = $(this);
            var identifier = $this.attr('id');
            var $latElement = $('#' + identifier + '-lat');
            var $lngElement = $('#' + identifier + '-lng');
            var $coordElement = $('#' + identifier + '-coords');
            var readonly = $(this).data('readonly');
            var useGeoLocation = true;
            var latitude = 0;
            var longitude = 0;
            var useJson = false;
            var separator = null;
            var showMarker = false;
            var singleElement = ($coordElement.length > 0);

            if(readonly) {
                useJson = !!$this.data('usejson');
                separator = $this.data('separator');

                useGeoLocation = false;
                showMarker = true;

                try {
                    coords = $this.data("data");
                    latitude = parseFloat(coords.lat) || 0;
                    longitude = parseFloat(coords.lng) || 0;
                } catch(e) {
                    coords = $this.data("data").split(separator);
                    latitude = parseFloat(coords[0]) || 0;
                    longitude = parseFloat(coords[1]) || 0;
                }
            } else {
                if(singleElement) {
                    var coords;
                    useJson = !!$coordElement.data('usejson');
                    separator = $coordElement.data('separator');

                    if($coordElement.val().length) {
                        useGeoLocation = false;
                        showMarker = true;

                        try {
                            coords = JSON.parse($coordElement.val());
                            latitude = parseFloat(coords.lat) || 0;
                            longitude = parseFloat(coords.lng) || 0;
                        } catch(e) {
                            coords = $coordElement.val().split(separator);
                            latitude = parseFloat(coords[0]) || 0;
                            longitude = parseFloat(coords[1]) || 0;
                        }
                    }
                } else {
                    if($latElement.val().length || $lngElement.val().length) {
                        useGeoLocation = false;
                        showMarker = true;
                        latitude = parseFloat($latElement.val()) || 0;
                        longitude = parseFloat($lngElement.val()) || 0;
                    }
                }
            }

            var centerLocation = new google.maps.LatLng(latitude, longitude);
            var markerLocation = new google.maps.LatLng(latitude, longitude);

            if(useGeoLocation && navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    centerLocation = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
                }, function() {
                    console.log("Your browser not support Geolocation");
                });
            } else if(useGeoLocation) {
                console.log("Your browser not support Geolocation");
            }

            var map = new google.maps.Map($this[0], {
                zoom: 4,
                center: centerLocation
            });

            $this.data('map', map);

            if (showMarker) {
                placeMarker($this, markerLocation);
                map.setZoom(16);
            }

            if(!readonly) {
                google.maps.event.addListener(map, 'click', function (event) {
                    placeMarker($this, event.latLng);

                    if (singleElement) {
                        var coords = {lat: event.latLng.lat().toFixed(6), lng: event.latLng.lng().toFixed(6)};
                        if (useJson) {
                            $coordElement.val(JSON.stringify(coords));
                        } else {
                            $coordElement.val(coords.lat + separator + coords.lng);
                        }
                    } else {
                        $latElement.val(event.latLng.lat().toFixed(6));
                        $lngElement.val(event.latLng.lng().toFixed(6));
                    }
                });

                $latElement.on('change', function () {
                    placeMarker($this, new google.maps.LatLng(parseFloat($(this).val()) || 0, parseFloat($lngElement.val()) || 0));
                });

                $lngElement.on('change', function () {
                    placeMarker($this, new google.maps.LatLng(parseFloat($latElement.val()) || 0, parseFloat($(this).val()) || 0));
                });
            }
        });
    });
})(jQuery);