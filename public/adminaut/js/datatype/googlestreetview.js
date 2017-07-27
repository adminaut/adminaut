function updatePanorama($element, data, status) {
    if (status === 'OK') {
        var panorama = $element.data('panorama');
        panorama.setPano(data.location.pano);
        panorama.setPov({
            heading: 270,
            pitch: 0
        });
        panorama.setVisible(true);
        $element.data('map').setCenter(data.location.latLng);
        $element.find('.remove-location-button').show();
    } else {
        console.error('Street View data not found for this location.');
    }
}

function getPanoData($element) {
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
    $element.data('map').setCenter(panorama.getPosition());
    $element.find('.remove-location-button').show();
}

(function ($) {
    appendScript('https://maps.googleapis.com/maps/api/js?libraries=places&key=' + google_api);

    $(window).load(function () {
        $('.datatype-streetview').each(function () {
            var $this = $(this);
            var identifier = $this.attr('id');
            var $input = $('#' + identifier + '-input').data('identifier', identifier);
            var $mapDiv = $('#' + identifier + '-map').data('identifier', identifier);
            var $panoDiv = $('#' + identifier + '-panorama').data('identifier', identifier);
            var useGeoLocation = true;
            var latitude = 0;
            var longitude = 0;
            var pano = "";
            var povHeading = 0;
            var povPitch = 0;
            var data = {};
            var init = true;
            var defaultCenter = $mapDiv.data('default-center');
            var defaultZoomLevel = $mapDiv.data('default-zoom-level');
            var downloadLocation = $this.data('download-location');
            var $locationLatitudeInput = $('input[name="' + $this.data('location-latitude-input') + '"]');
            var $locationLongitudeInput = $('input[name="' + $this.data('location-longitude-input') + '"]');
            var centerLocation;
            var $gmButtonContainer = $this.find('.gm-buttons-container');

            if($input.val()) {
                data = JSON.parse($input.val());
                latitude = data.latitude;
                longitude = data.longitude;
                pano = data.pano;
                povHeading = data.povHeading;
                povPitch = data.povPitch;
                useGeoLocation = false;
                centerLocation = new google.maps.LatLng(latitude, longitude);
            } else {
                if(downloadLocation === 'automatic' && ($locationLatitudeInput.val().length || $locationLongitudeInput.val().length)) {
                    centerLocation = new google.maps.LatLng($locationLatitudeInput.val(), $locationLongitudeInput.val());
                    useGeoLocation = false;
                } else {
                    if (defaultCenter) {
                        centerLocation = new google.maps.LatLng(defaultCenter.latitude, defaultCenter.longitude);
                    } else {
                        centerLocation = new google.maps.LatLng(0, 0);
                    }
                }
            }

            if (useGeoLocation && navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function (position) {
                    centerLocation = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
                }, function () {
                    console.log("Your browser not support Geolocation");
                });
            } else if (useGeoLocation) {
                console.log("Your browser not support Geolocation");
            }

            var map = new google.maps.Map($mapDiv[0], {
                zoom: defaultZoomLevel || 4,
                center: centerLocation,
                scrollwheel: false
            });
            var sv = new google.maps.StreetViewService();
            var panorama = new google.maps.StreetViewPanorama($panoDiv[0], {
                position: centerLocation,
                pov: {
                    heading: povHeading,
                    pitch: povPitch
                },
                scrollwheel: false,
                fullscreenControl: false,
                motionTracking: false,
                motionTrackingControl: false
            });
            map.setStreetView(panorama);

            $this.data('map', map);
            $this.data('panorama', panorama);

            if(downloadLocation !== 'disabled') {
                map.controls[google.maps.ControlPosition.TOP_RIGHT].push($gmButtonContainer[0]);

                $gmButtonContainer.find('.download-location-button').on('click', function() {
                    var location = new google.maps.LatLng($locationLatitudeInput.val(), $locationLongitudeInput.val());
                    sv.getPanorama({location: location, radius: 100}, function(data, status) {
                        updatePanorama($this, data, status);
                    });
                    map.setZoom(18);
                });

                if(downloadLocation === 'automatic') {
                    $locationLatitudeInput.on('change', function() {
                        var location = new google.maps.LatLng($locationLatitudeInput.val(), $locationLongitudeInput.val());
                        sv.getPanorama({location: location, radius: 50}, function(data, status) {
                            updatePanorama($this, data, status);
                        });
                        map.setZoom(18);
                    });
                    $locationLongitudeInput.on('change', function() {
                        var location = new google.maps.LatLng($locationLatitudeInput.val(), $locationLongitudeInput.val());
                        sv.getPanorama({location: location, radius: 50}, function(data, status) {
                            updatePanorama($this, data, status);
                        });
                        map.setZoom(18);
                    });
                }
            }

            $gmButtonContainer.find('.remove-location-button').on('click', function() {
                map.setCenter(defaultCenter);
                map.setZoom(defaultZoomLevel || 4);
                panorama.setVisible(false);
                $input.val('');
                $(this).hide();
            });

            map.setZoom(Object.keys(data).length > 0 ? 18 : defaultZoomLevel || 4);
            if (Object.keys(data).length === 0) {
                $gmButtonContainer.find('.remove-location-button').hide();
            }

            map.addListener('click', function(event) {
                map.setOptions({scrollwheel:true});
                sv.getPanorama({location: event.latLng, radius: 50}, function(data, status) {
                    updatePanorama($this, data, status);
                });
            });

            google.maps.event.addListener(map, 'center_changed', function(event) {
                this.setOptions({scrollwheel:true});
            });

            google.maps.event.addListener(map, 'mouseout', function(event){
                this.setOptions({scrollwheel:false});
            });

            panorama.addListener('pano_changed', function() {
                getPanoData($this);
            });

            panorama.addListener('position_changed', function() {
                getPanoData($this);
            });

            panorama.addListener('pov_changed', function() {
                getPanoData($this);
            });
        });

        var style = '<style>'
            + '.gm-button { background-color: #ffffff; border: 0; border-radius: 2px; box-shadow: rgba(0, 0, 0, 0.298039) 0 1px 4px -1px; width: 29px; height: 29px; vertical-align: top; }'
            + '.gm-buttons-container { margin-right: 10px; margin-top: 10px; }'
            + '.download-location-button { margin-bottom: 3px; display: block; }'
            + '</style>';
        $('head').append(style);
    });
})(jQuery);