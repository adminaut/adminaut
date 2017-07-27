<?php

namespace Adminaut\Datatype;

/**
 * Class Textarea
 * @package Adminaut\Datatype
 */
class Textarea extends \Zend\Form\Element\Textarea implements DatatypeInterface
{
    use Datatype {
        setOptions as datatypeSetOptions;
    }

    /**
     * Available editors
     */
    const EDITOR_NONE = 'none';
    const EDITOR_BOOTSTRAP = 'bootstrap';
    const EDITOR_CKEDITOR = 'ckeditor';
    const EDITOR_TINYMCE = 'tinymce';

    const EDITORS = [self::EDITOR_NONE, self::EDITOR_BOOTSTRAP, self::EDITOR_CKEDITOR, self::EDITOR_TINYMCE];

    protected $attributes = [
        'type' => 'datatypeTextarea',
    ];

    protected $editor = 'none';

    protected $height = 250;

    protected $maxHeight = 500;

    protected $autosize = false;

    /**
     * @param $editor
     */
    public function setEditor($editor)
    {
        if (in_array($editor, self::EDITORS)) {
            $this->editor = $editor;
        }
    }

    /**
     * @return string
     */
    public function getEditor()
    {
        return $this->editor;
    }

    /**
     * @param int $height
     */
    public function setHeight($height)
    {
        $this->height = $height;
    }

    /**
     * @return int
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @param int $maxHeight
     */
    public function setMaxHeight($maxHeight)
    {
        $this->maxHeight = $maxHeight;
    }

    /**
     * @return int
     */
    public function getMaxHeight()
    {
        return $this->maxHeight;
    }

    /**
     * @param bool $autosize
     */
    public function setAutosize($autosize)
    {
        $this->autosize = $autosize;
    }

    /**
     * @return bool
     */
    public function getAutosize()
    {
        return $this->autosize;
    }

    /**
     * @param array|\Traversable $options
     * @return $this
     */
    public function setOptions($options)
    {
        if (isset($options['editor'])) {
            $this->setEditor($options['editor']);
        }

        if (isset($options['height'])) {
            $this->setHeight($options['height']);
        }

        if (isset($options['maxHeight'])) {
            $this->setMaxHeight($options['maxHeight']);
        }

        if (isset($options['autosize'])) {
            $this->setAutosize($options['autosize']);
        }

        $this->datatypeSetOptions($options);
        return $this;
    }

    /**
     * @return array
     */
    public function getAttributes()
    {
        $this->setAttribute('id', $this->attributes['name']);
        return $this->attributes;
    }
}
