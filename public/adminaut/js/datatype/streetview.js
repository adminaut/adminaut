function placeMarker($element, location) {
    console.log("placeMarker");
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

function updatePanorama($element, data, status) {
    console.log("updatePanorama");
    if (status === 'OK') {
        placeMarker($element, data.location.latLng);

        var panorama = $element.data('panorama');
        panorama.setPano(data.location.pano);
        panorama.setPov({
            heading: 270,
            pitch: 0
        });
        panorama.setVisible(true);
    } else {
        console.error('Street View data not found for this location.');
    }
}

function getPanoData($element) {
    console.log("getPanoData");
    var identifier = $element.attr('id');
    var panorama = $element.data('panorama');
    var $input = $('#' + identifier + '-input');
    var data = {};
    if($input.val()) {
        data = JSON.parse($input.val());
    }


    data.latitude = panorama.getPosition().lat();
    data.longitude = panorama.getPosition().lng();
    data.pano = panorama.getPano();
    data.povHeading = panorama.getPov().heading;
    data.povPitch = panorama.getPov().pitch;

    $input.val(JSON.stringify(data));
    placeMarker($element, panorama.getPosition());
    $element.data('map').setCenter(panorama.getPosition());
}

(function ($) {
    appendScript('https://maps.googleapis.com/maps/api/js?key=' + google_api);

    $(window).load(function () {
        $('.datatype-streetview').each(function () {
            var $this = $(this);
            var identifier = $this.attr('id');
            var $input = $('#' + identifier + '-input').data('identifier', identifier);
            var $mapDiv = $('#' + identifier + '-map').data('identifier', identifier);
            var $panoDiv = $('#' + identifier + '-panorama').data('identifier', identifier);
            var useGeoLocation = true;
            var showMarker = false;
            var latitude = 0;
            var longitude = 0;
            var pano = "";
            var povHeading = 0;
            var povPitch = 0;
            var data = {};
            var init = true;

            if($input.val()) {
                data = JSON.parse($input.val());
                latitude = data.latitude;
                longitude = data.longitude;
                pano = data.pano;
                povHeading = data.povHeading;
                povPitch = data.povPitch;
                showMarker = true;
                useGeoLocation = false;
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

            var map = new google.maps.Map($mapDiv[0], {
                zoom: 4,
                center: centerLocation,
                streetViewControl: false
            });
            var sv = new google.maps.StreetViewService();
            var panorama = new google.maps.StreetViewPanorama($panoDiv[0], {
                position: markerLocation,
                pov: {
                    heading: povHeading,
                    pitch: povPitch
                }
            });

            $this.data('map', map);
            $this.data('panorama', panorama);

            if (showMarker) {
                placeMarker($this, markerLocation);
                map.setZoom(16);
            } else {
                map.setZoom(4);
                map.setCenter(centerLocation);
                sv.getPanorama({location: markerLocation, radius: 50}, function(data, status) {
                    updatePanorama($this, data, status);
                });
            }

            map.addListener('click', function(event) {
                sv.getPanorama({location: event.latLng, radius: 50}, function(data, status) {
                    updatePanorama($this, data, status);
                });
            });

            panorama.addListener('pano_changed', function() {
                console.log('pano_changed', panorama.getPosition().toString(), init);
                getPanoData($this);
            });

            panorama.addListener('position_changed', function() {
                console.log('position_changed', panorama.getPosition().toString(), init);
                getPanoData($this);
            });

            panorama.addListener('pov_changed', function() {
                console.log('pov_changed', panorama.getPosition().toString(), init);
                getPanoData($this);
            });
        });
    });
})(jQuery);