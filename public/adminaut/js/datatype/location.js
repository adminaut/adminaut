jQuery.fn.locationDatatype = function() {
    var $datatype = $(this);
    this.data = $datatype.data();

    this.useHiddenElement = this.data.useHiddenElement;
    //this.saveAs = this.data.saveAs;
    this.separator = this.data.separator;
    this.engine = this.data.engine;
    this.$mainInput = $('input[name="'+ this.data.mainInput +'"]');
    this.$searchContainer = $datatype.find('.datatype-location-search-container');
    this.readOnly = this.data.readonly;
    this.value = this.data.value;
    this.defaultCenter = this.data.defaultCenter;
    this.defaultZoomLevel = this.data.defaultZoomLevel;
    this.enableDownloadData = this.data.enableDownloadData;
    this.downloadDataFrom = this.data.downloadDataFrom;
    this.$longitudeElement = $('input[name="'+ this.data.longitudeElementName +'"]');

    if(this.engine === 'google') {
        this.googleMode = this.data.googleMode;
        this.googlePlaceFilter = this.data.googlePlaceFilter;

        if(this.data.googlePlaceIdElementName) {
            this.$googlePlaceIdElement = $('input[name="'+ this.data.googlePlaceIdElementName +'"]');
        }
    }

    this.map = null;
    this.marker = null;
    this.useGeoLocation = false;

    this.init = function() {
        var $datatype = $(this);
        this.prepareContainer();
        this.map = this.initMap();
        var value = this.getValue();
        if(typeof(value.latitude) === 'number' && typeof(value.longitude) === 'number' ) {
            this.placeMarker(value);
            this.setCenter(value);
            this.setZoom(18);
        } else {
            if(this.defaultCenter) {
                this.setCenter(this.defaultCenter);
            } else {
                this.setCenter({latitude: 0, longitude: 0});
            }

            if(this.defaultCenter) {
                this.setZoom(this.defaultZoomLevel);
            } else {
                this.setZoom(4);
            }

            this.$searchContainer.find('.remove-data-button').hide();
        }

        if(!this.readOnly) {
            this.initSearch();
            this.initClickListener();
            this.initDownloadDataListener();
            this.initRemoveDataListener();
            this.initLocationInputsChangeListener();
        }

        this.initCenterChangedListener();
        this.initMouseOutListener();
    };

    this.prepareContainer = function() {

        var $renderInput = $('<div class="row"></div>');
        if(!this.readonly) {
            $renderInput.append($('<div class="col-xs-6"></div>').append(this.$mainInput.addClass('form-control')));
            if (this.$longitudeElement) {
                $renderInput.append($('<div class="col-xs-6"></div>').append(this.$longitudeElement.addClass('form-control')));
            }
        }

        var $renderMap = $('<div class="row"></div>');
        var $mapContainer = $('<div class="datatype-location-map-container" style="margin-top: 15px; min-height: 300px;"></div>').appendTo($('<div class="col-xs-12"></div>').appendTo($renderMap));
        if(!this.readOnly) {
            $mapContainer.append(this.$searchContainer);
        }
        // var $renderMap = $('<div class="row"><div class="col-xs-12"><div class="datatype-location-map-container" style="margin-top: 15px; min-height: 300px;">' +  + '</div></div></div>')
        $(this).html($renderInput).append($renderMap);
    };

    this.setCenter = function(location) {
        if(this.engine === 'google') {
            this.map.setCenter({lat: location.latitude, lng: location.longitude});
        }
    };

    this.initMap = function() {
        if(this.engine === 'google') {
            return new google.maps.Map($(this).find('.datatype-location-map-container')[0], {
                scrollwheel: false
            });
        }
    };

    this.placeMarker = function(location) {
        if(this.engine === 'google') {
            if (this.marker === null) {
                this.marker = new google.maps.Marker({
                    position: {lat: location.latitude, lng: location.longitude},
                    map: this.map
                });
            } else {
                this.marker.setPosition({lat: location.latitude, lng: location.longitude});
            }
        }
    };

    this.removeMarker = function() {
        if(this.engine === 'google') {
            if (this.marker !== null) {
                this.marker.setMap(null);
                this.marker = null;
            }
        }
    };

    this.getGooglePlaceInfo = function(placeid, callback) {
        var $datatype = $(this);

        if(placeid) {
            if (this.place) {
                if (this.place.place_id === placeid) {
                    return this.place;
                }
            } else {
                var placeidService = new google.maps.places.PlacesService(this.map);
                var request = {
                    placeId: placeid
                };

                var self = this;
                placeidService.getDetails(request, function (place, status) {
                    if (status === google.maps.places.PlacesServiceStatus.OK) {
                        self.place = place;
                    }
                });
            }

            return this.place;
        }
    };

    this.setZoom = function(level) {
        this.map.setZoom(level);
    };

    this.getValue = function() {
        if(typeof(this.value) === 'object' && this.value.latitude !== undefined) {
            if(typeof(this.value.latitude) !== 'number') {
                this.value.latitude = parseFloat(this.value.latitude);
                this.value.longitude = parseFloat(this.value.longitude);
            }
            return this.value;
        } else {
            if(this.engine === 'google') {
                if(this.value.googlePlaceId !== undefined) {
                    console.log(this.value);
                    var place = this.getGooglePlaceInfo(this.value.googlePlaceId);

                    this.value.latitude = place.geometry.location.lat;
                    this.value.longitude = place.geometry.location.lng;
                }
            }
        }

        return this.value;
    };

    this.initSearch = function() {
        $datatype = $(this);
        var self = this;

        if(this.engine === 'google') {
            this.autocomplete = new google.maps.places.Autocomplete(this.$searchContainer.find('.search-input')[0]);
            this.autocomplete.bindTo('bounds', this.map);
            this.map.controls[google.maps.ControlPosition.TOP_LEFT].push(this.$searchContainer[0]);

            this.autocomplete.addListener('place_changed', function() {
                var place = self.autocomplete.getPlace();
                if (!place.geometry) {
                    return;
                }

                self.value.googlePlaceId = place.place_id;
                self.value.latitude = place.geometry.location.lat();
                self.value.longitude = place.geometry.location.lng();
                self.placeMarker(self.value);
                self.setCenter(self.value);
                self.setZoom(18);
                self.updateInputsValue();
            });
        }
    };

    this.initClickListener = function() {
        var self = this;

        if(this.engine === 'google') {
            google.maps.event.addListener(this.map, 'click', function (event) {
                self.map.setOptions({scrollwheel:true});
                self.getGooglePlaceInfo(event.placeId);
                if(self.googleMode === 'coordinates') {
                    self.value.latitude = event.latLng.lat();
                    self.value.longitude = event.latLng.lng();

                    if(event.placeId) {
                        self.value.googlePlaceId = event.placeId;
                    }
                } else if(self.googleMode === 'places') {
                    if(event.placeId) {
                        self.value.latitude = event.latLng.lat();
                        self.value.longitude = event.latLng.lng();
                        self.value.googlePlaceId = event.placeId;
                    }
                }

                if(event.placeId === undefined) {
                    self.value.googlePlaceId = "";
                }

                self.placeMarker(self.value);
                self.updateInputsValue();
            });
        }
    };

    this.initDownloadDataListener = function() {
        var self = this;

        if(this.$searchContainer.find('.download-data-button').length) {
            this.$searchContainer.find('.download-data-button').on('click', function() {
                var result = [];

                $.each(self.downloadDataFrom, function(key, value) {
                    result.push($(document).find('*[name="'+value+'"]').val());
                });

                self.$searchContainer.find('.search-input').val(result.join(', ')).focus();
            });
        }
    };

    this.initRemoveDataListener = function() {
        var self = this;

        if(this.$searchContainer.find('.remove-data-button').length) {
            this.$searchContainer.find('.remove-data-button').on('click', function() {
                self.$mainInput.val('');
                self.$longitudeElement.val('');

                if(self.engine === 'google') {
                    if(self.$googlePlaceIdElement) {
                        self.$googlePlaceIdElement.val('');
                    }
                }

                self.setCenter(self.defaultCenter);
                self.setZoom(self.defaultZoomLevel);
                self.removeMarker();
                $(this).hide();
            });
        }
    };

    this.initMouseOutListener = function() {
        if(this.engine === 'google') {
            google.maps.event.addListener(this.map, 'mouseout', function(event){
                this.setOptions({scrollwheel:false});
            });
        }
    };

    this.initCenterChangedListener = function() {
        if(this.engine === 'google') {
            google.maps.event.addListener(this.map, 'center_changed', function(event) {
                this.setOptions({scrollwheel:true});
            });
        }
    };

    this.initLocationInputsChangeListener = function() {
        var self = this;
        this.$mainInput.on('change', function() {
            self.value.latitude = parseFloat($(this).val());
            self.value.googlePlaceId = "";

            self.placeMarker(self.value);
            self.setCenter(self.value);
            if(self.$googlePlaceIdElement) {
                self.$googlePlaceIdElement.val(self.value.googlePlaceId).trigger('change');
            }
        });

        this.$longitudeElement.on('change', function() {
            self.value.longitude = parseFloat($(this).val());
            self.value.googlePlaceId = "";

            self.placeMarker(self.value);
            self.setCenter(self.value);
            if(self.$googlePlaceIdElement) {
                self.$googlePlaceIdElement.val(self.value.googlePlaceId).trigger('change');
            }
        });
    };

    this.updateInputsValue = function() {
        if(this.engine === 'google') {
            this.$mainInput.val(this.value.latitude.toFixed(6)).trigger('change');
            this.$longitudeElement.val(this.value.longitude.toFixed(6)).trigger('change');

            if(this.$googlePlaceIdElement) {
                this.$googlePlaceIdElement.val(this.value.googlePlaceId).trigger('change');
            }
        } else {
            this.$mainInput.val(this.value.latitude.toFixed(6)).trigger('change');
            this.$longitudeElement.val(this.value.longitude.toFixed(6)).trigger('change');
        }

        this.$searchContainer.find('.remove-data-button').show();
    };

    return this.init();
};

(function($) {
    appendScript('https://maps.googleapis.com/maps/api/js?libraries=places&key=' + google_api);
    var style = '<style>'
        + '.datatype-location-map-container { margin-top: 15px; min-height: 300px; }'
        + '.controls { display: none; }'
        + '.gm-style .controls { display: inline-block; }'
        + '.datatype-location-search-container { padding-top: 10px; }'
        + '.controls {background-color: #fff;border-radius: 2px;border: 1px solid transparent;box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);box-sizing: border-box;font-family: Roboto;font-size: 15px;font-weight: 300;height: 29px;margin-left: 17px;outline: none;padding: 0 11px 0 13px;text-overflow: ellipsis;width: 400px;vertical-align: top;}'
        + '.controls:focus {border-color: #4d90fe;}'
        + '.gm-button { background-color: #ffffff; border: 0; border-radius: 2px; box-shadow: rgba(0, 0, 0, 0.298039) 0 1px 4px -1px; width: 29px; height: 29px; vertical-align: top; }'
        + '</style>';

    $('head').append(style);

    $(window).load(function(){
        $('.datatype-location').each(function(){
            $(this).locationDatatype();
        });
    });
})(jQuery);