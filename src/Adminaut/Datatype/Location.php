<?php

namespace Adminaut\Datatype;

use Adminaut\Datatype\Location\Exception\InvalidDefaultCenterException;
use Adminaut\Datatype\Location\Exception\InvalidEngineTypeException;
use Adminaut\Datatype\Location\Exception\InvalidGoogleModeException;
use Adminaut\Datatype\Location\Exception\InvalidSaveTypeException;
use Zend\Form\Element;

/**
 * Class Location
 * @package Adminaut\Datatype
 */
class Location extends Element
{
    /** Engines contants */
    const ENGINE_GOOGLE = "google";

    /** Google Modes constants */
    const GOOGLE_MODE_COORDINATES = "coordinates";
    const GOOGLE_MODE_PLACES = "places";
    const GOOGLE_MODE_FULL = "full";

    /** Datatype defaults */
    use Datatype {
        setOptions as datatypeSetOptions;
    }

    /**
     * @var bool
     */
    protected $useHiddenElement = false;

    /**
     * Available map engines: google
     *
     * @var string
     */
    protected $engine = self::ENGINE_GOOGLE;

    /** --- Google Engine --- */
    /**
     * Available google modes: coordinates, places, full
     *
     * @var string
     */
    protected $googleMode = self::GOOGLE_MODE_COORDINATES;

    /**
     * @var array
     */
    protected $googlePlacesFilter = [];

    /**
     * @var string|null
     */
    protected $googlePlaceIdProperty = null;

    /**
     * @var Element|null
     */
    protected $googlePlaceIdElement = null;
    /** --- END Google Engine */

    /**
     * @var string|null
     */
    protected $longitudeProperty = null;

    /**
     * @var Element|null
     */
    protected $longitudeElement = null;

    /**
     * @var bool
     */
    protected $readOnly = false;

    /**
     * @var array
     */
    protected $defaultCenter = null;

    /**
     * @var int
     */
    protected $defaultZoomLevel = null;

    /**
     * @var bool
     */
    protected $enableDownloadData = false;

    /**
     * @var array|null
     */
    protected $downloadDataFrom = null;


    /**
     * @var array
     */
    protected $attributes = [
        'type' => 'datatypeLocation',
    ];

    /**
     * @param  array|\Traversable $options
     * @return self
     */
    public function setOptions($options)
    {
        if (isset($options['use_hidden_element'])) {
            $this->setUseHiddenElement($options['use_hidden_element']);
        }

        if(isset($options['engine'])) {
            $this->setEngine($options['engine']);
        }

        if(isset($options['longitude_property'])) {
            $this->setLongitudeProperty($options['longitude_property']);
        }

        if(isset($options['default_center'])) {
            $this->setDefaultCenter($options['default_center']);
        }

        if(isset($options['default_zoom'])) {
            $this->setDefaultZoomLevel($options['default_zoom']);
        }

        if(isset($options['default_zoom_level'])) {
            $this->setDefaultZoomLevel($options['default_zoom_level']);
        }

        /** Google engine */
        if(isset($options['google_mode'])) {
            $this->setGoogleMode($options['google_mode']);
        }

        if(isset($options['google_place_filter'])) {
            $this->setGooglePlacesFilter($options['google_place_filter']);
        }

        if(isset($options['google_place_id_property'])) {
            $this->setGooglePlaceIdProperty($options['google_place_id_property']);
        }

        if(isset($options['enable_download_data'])) {
            $this->setEnableDownloadData($options['enable_download_data']);
        }

        if(isset($options['download_data_from']) && $this->isEnableDownloadData()) {
            $this->setDownloadDataFrom($options['download_data_from']);
        }


        $this->datatypeSetOptions($options);
        return $this;
    }



    /**
     * @return array
     */
    public function getAttributes()
    {
        $this->attributes['id'] = 'datatype-location-' . $this->getName();
        $this->attributes['class'] = 'datatype-location';
        $this->attributes['data-use-hidden-element'] = $this->isUseHiddenElement();
        $this->attributes['data-main-input'] = $this->getName();
        $this->attributes['data-engine'] = $this->getEngine();
        $this->attributes['data-readonly'] = $this->isReadOnly();
        if($this->getEngine() === self::ENGINE_GOOGLE) {
            $this->attributes['data-google-mode'] = $this->getGoogleMode();
            $this->attributes['data-google-place-filter'] = json_encode($this->getGooglePlacesFilter());
        }
        return $this->attributes;
    }

    /**
     * @return bool
     */
    public function isUseHiddenElement()
    {
        return $this->useHiddenElement;
    }

    /**
     * @param bool $useHiddenElement
     */
    public function setUseHiddenElement($useHiddenElement)
    {
        $this->useHiddenElement = $useHiddenElement;
    }

    /**
     * @return string
     */
    public function getEngine()
    {
        return $this->engine;
    }

    /**
     * @param string $engine
     */
    public function setEngine($engine)
    {
        $availableEngines = [self::ENGINE_GOOGLE];
        if(!in_array($engine, $availableEngines)) {
            throw new InvalidEngineTypeException('Invalid map engine type. Available map engines: ' . implode(', ', $availableEngines));
        } else {
            $this->engine = $engine;
        }
    }

    /**
     * @return string
     */
    public function getGoogleMode()
    {
        return $this->googleMode;
    }

    /**
     * @param string $googleMode
     */
    public function setGoogleMode($googleMode)
    {
        $availableModes = [self::GOOGLE_MODE_COORDINATES, self::GOOGLE_MODE_PLACES, self::GOOGLE_MODE_FULL];
        if(!in_array($googleMode, $availableModes)) {
            throw new InvalidGoogleModeException('Invalid Google mode. Available modes: ' . implode(',', $availableModes));
        } else {
            $this->googleMode = $googleMode;
        }
    }

    /**
     * @return array
     */
    public function getGooglePlacesFilter()
    {
        return $this->googlePlacesFilter;
    }

    /**
     * @param array $googlePlacesFilter
     */
    public function setGooglePlacesFilter($googlePlacesFilter)
    {
        $this->googlePlacesFilter = $googlePlacesFilter;
    }

    /**
     * @return null|string
     */
    public function getGooglePlaceIdProperty()
    {
        return $this->googlePlaceIdProperty;
    }

    /**
     * @param null|string $googlePlaceIdProperty
     */
    public function setGooglePlaceIdProperty($googlePlaceIdProperty)
    {
        $this->googlePlaceIdProperty = $googlePlaceIdProperty;
    }

    /**
     * @return null|Element
     */
    public function getGooglePlaceIdElement()
    {
        return $this->googlePlaceIdElement;
    }

    /**
     * @param null|Element $googlePlaceIdElement
     */
    public function setGooglePlaceIdElement($googlePlaceIdElement)
    {
        $this->googlePlaceIdElement = $googlePlaceIdElement;
    }

    /**
     * @return null|string
     */
    public function getLongitudeProperty()
    {
        return $this->longitudeProperty;
    }

    /**
     * @param null|string $longitudeProperty
     */
    public function setLongitudeProperty($longitudeProperty)
    {
        $this->longitudeProperty = $longitudeProperty;
    }

    /**
     * @return null|Element
     */
    public function getLongitudeElement()
    {
        return $this->longitudeElement;
    }

    /**
     * @param null|Element $longitudeElement
     */
    public function setLongitudeElement($longitudeElement)
    {
        $this->longitudeElement = $longitudeElement;
    }

    public function getEditValue()
    {
        return $this->getValue();
    }

    /**
     * @return bool
     */
    public function isReadOnly()
    {
        return $this->readOnly;
    }

    /**
     * @param bool $readOnly
     */
    public function setReadOnly($readOnly)
    {
        $this->readOnly = $readOnly;
    }

    /**
     * @return array
     */
    public function getDefaultCenter()
    {
        return $this->defaultCenter;
    }

    /**
     * @param array $defaultCenter
     */
    public function setDefaultCenter($defaultCenter)
    {
        if(!isset($defaultCenter['latitude']) && !isset($defaultCenter['lat'])) {
            throw new InvalidDefaultCenterException('Missing latitude property.');
        } elseif(!isset($defaultCenter['longitude']) && !isset($defaultCenter['lng'])) {
            throw new InvalidDefaultCenterException('Missing longitude property.');
        } else {
            $defaultCenter['latitude'] = $defaultCenter['lat'];
            $defaultCenter['longitude'] = $defaultCenter['lng'];
            unset($defaultCenter['lat'], $defaultCenter['lng']);
            $this->defaultCenter = $defaultCenter;
        }
    }

    /**
     * @return int
     */
    public function getDefaultZoomLevel()
    {
        return $this->defaultZoomLevel;
    }

    /**
     * @param int $defaultZoomLevel
     */
    public function setDefaultZoomLevel($defaultZoomLevel)
    {
        $this->defaultZoomLevel = $defaultZoomLevel;
    }

    /**
     * @return bool
     */
    public function isEnableDownloadData()
    {
        return $this->enableDownloadData;
    }

    /**
     * @param bool $enableDownloadData
     */
    public function setEnableDownloadData($enableDownloadData)
    {
        $this->enableDownloadData = $enableDownloadData;
    }

    /**
     * @return array|null
     */
    public function getDownloadDataFrom()
    {
        return $this->downloadDataFrom;
    }

    /**
     * @param array|null $downloadDataFrom
     */
    public function setDownloadDataFrom($downloadDataFrom)
    {
        $this->downloadDataFrom = $downloadDataFrom;
    }
}
