<?php

namespace Adminaut\Datatype;

use Zend\Validator\Regex;

/**
 * Class Color
 * @package Adminaut\Datatype
 */
class Color extends \Zend\Form\Element\Color
{
    const FORMAT_HSL = 'hsl';
    const FORMAT_HSLA = 'hsla';
    const FORMAT_RGB = 'rgb';
    const FORMAT_RGBA = 'rgba';
    const FORMAT_HEX = 'hex';

    use Datatype {
        setOptions as datatypeSetOptions;
    }

    /**
     * @var array
     */
    protected $attributes = [
        'type' => 'datatypeColor',
    ];

    /**
     * @var bool|string
     */
    protected $format = false;

    /**
     * @param array|\Traversable $options
     */
    public function setOptions($options)
    {
        if (!isset($options['add-on-prepend'])) {
            $options['add-on-prepend'] = '<i></i>';
        }

        if (!isset($options['twb-row-class'])) {
            $options['twb-row-class'] = 'datatype-color';
        }

        if (isset($options['format'])) {
            $this->format = $options['format'];
        }

        self::datatypeSetOptions($options);
    }

    /**
     * Get validator
     *
     * @return \Zend\Validator\ValidatorInterface
     */
    protected function getValidator()
    {
        switch ($this->format) {
            case self::FORMAT_HSL : {
                $this->validator = new Regex('/^hsl[(]\s*0*(?:[12]?\d{1,2}|3(?:[0-5]\d|60))\s*(?:\s*,\s*0*(?:\d\d?(?:\.\d+)?\s*%|\.\d+\s*%|100(?:\.0*)?\s*%)){2}\s*[)]$/');
                break;
            }

            case self::FORMAT_HSLA : {
                $this->validator = new Regex('/^(hsla\(\s*(-?\d+|-?\d*.\d+)\s*,\s*(-?\d+|-?\d*.\d+)%\s*,\s*(-?\d+|-?\d*.\d+)%\s*,\s*(-?\d+|-?\d*.\d+)\s*\))$/');
                break;
            }

            case self::FORMAT_RGB : {
                $this->validator = new Regex('/rgb\(?([01]?\d\d?|2[0-4]\d|25[0-5])\s*,\s*([01]?\d\d?|2[0-4]\d|25[0-5])\s*,\s*(([01]?\d\d?|2[0-4]\d|25[0-5])\))$/');
                break;
            }

            case self::FORMAT_RGBA : {
                $this->validator = new Regex('/^hsla\(\s*0*(?:[12]?\d{1,2}|3(?:[0-5]\d|60))\s*(?:\s*,\s*0*(?:\d\d?(?:\.\d+)?\s*%|\.\d+\s*%|100(?:\.0*)?\s*%)){2}\s*,\s*(1|0?(\.\d+)?)\)$/');
                break;
            }

            case self::FORMAT_HEX : {
                $this->validator = new Regex('/^#(?:[A-Fa-f0-9]{3}){1,2}$/');
                break;
            }

            default: {
                $this->validator = new Regex('/^(?:#(?:[A-Fa-f0-9]{3}){1,2}|(?:rgb[(](?:\s*0*(?:\d\d?(?:\.\d+)?(?:\s*%)?|\.\d+\s*%|100(?:\.0*)?\s*%|(?:1\d\d|2[0-4]\d|25[0-5])(?:\.\d+)?)\s*(?:,(?![)])|(?=[)]))){3}|hsl[(]\s*0*(?:[12]?\d{1,2}|3(?:[0-5]\d|60))\s*(?:\s*,\s*0*(?:\d\d?(?:\.\d+)?\s*%|\.\d+\s*%|100(?:\.0*)?\s*%)){2}\s*|(?:rgba[(](?:\s*0*(?:\d\d?(?:\.\d+)?(?:\s*%)?|\.\d+\s*%|100(?:\.0*)?\s*%|(?:1\d\d|2[0-4]\d|25[0-5])(?:\.\d+)?)\s*,){3}|hsla[(]\s*0*(?:[12]?\d{1,2}|3(?:[0-5]\d|60))\s*(?:\s*,\s*0*(?:\d\d?(?:\.\d+)?\s*%|\.\d+\s*%|100(?:\.0*)?\s*%)){2}\s*,)\s*0*(?:\.\d+|1(?:\.0*)?)\s*)[)]|transparent|aqua(?:marine)?|azure|beige|bisque|black|blanchedalmond|blue(?:violet)?|(?:alice|dodger|cadet|midnight|powder|royal|sky|slate|steel)blue|(?:rosy|saddle|sandy)?brown|burlywood5|chartreuse|chocolate|coral|corn(?:flowerblue|silk)|crimson|cyan|dark(?:(?:slate)?blue|cyan|goldenrod|(?:olive|sea)?green|(?:slate)?gr[ae]y|khaki|magenta|orange|orchid|red|salmon|turquoise|violet)|deep(?:pink|skyblue)|firebrick|fuchsia|gainsboro|gold(?:enrod)?|(?:dim|slate)?gr[ae]y|(?:forest|lawn|spring)?green|greenyellow|honeydew|indigo|ivory|khaki|lavender(?:blush)?|lemonchiffon|light(?:(?:sky|steel)?blue|coral|cyan|goldenrodyellow|(?:slate)?gr[ae]y|green|pink|salmon|seagreen|yellow)|lime(?:green)?|linen|magenta|maroon|medium(?:aquamarine|(?:slate)?blue|orchid|purple|s(?:ea|pring)green|turquoise|violetred)|mintcream|mistyrose|moccasin|navy|oldlace|olive(?:drab)?|orange(?:red)?|orchid|pale(?:goldenrod|green|turquoise|violetred)|papayawhip|peachpuff|peru|(?:hot)?pink|plum|purple|(?:indian)?red|salmon|sea(?:green|shell)|sienna|silver|snow|tan|teal|thistle|tomato|turquoise|violet|wheat|whitesmoke|(?:antique|floral|ghost|navajo)?white|yellow(?:green)?)$/');
            }
        }

        $messageFormats = 'hsl, hsla, rgb, rgba, hex';
        if($this->format) {
            $messageFormats = $this->format;
        }

        $this->validator->setMessages([
            Regex::NOT_MATCH => "Invalid format, valid formats: " . $messageFormats,
        ]);

        return $this->validator;
    }

    /**
     * @return mixed
     */
    public function getListedValue()
    {
        return $this->getValue();
    }

    /**
     * @return mixed
     */
    public function getInsertValue()
    {
        return $this->getValue();
    }

    /**
     * @return mixed
     */
    public function getEditValue()
    {
        return $this->getValue();
    }

    /**
     * @return array
     */
    public function getAttributes()
    {
        $this->attributes['id'] = $this->attributes['name'];
        return $this->attributes;
    }

    /**
     * @return bool|string
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * @param bool|string $format
     */
    public function setFormat($format)
    {
        $this->format = $format;
    }
}
