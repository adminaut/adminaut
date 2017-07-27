<?php

namespace Adminaut\Datatype\Textarea;

use Adminaut\Datatype\Textarea;
use Zend\Form\ElementInterface;
use Zend\Form\Exception\InvalidArgumentException;
use Zend\Form\View\Helper\FormTextarea;

/**
 * Class FormViewHelper
 * @package Adminaut\Datatype\Textarea
 */
class FormViewHelper extends FormTextarea
{

    /**
     * @param ElementInterface|null $element
     * @return FormViewHelper|string
     */
    public function __invoke(ElementInterface $element = null)
    {
        if (null === $element) {
            return $this;
        }

        return $this->render($element);
    }

    /**
     * @param ElementInterface $element
     * @return string
     */
    public function render(ElementInterface $element)
    {
        if (!$element instanceof Textarea) {
            throw new InvalidArgumentException(sprintf(
                '%s requires that the element is of type ' . Textarea::class,
                __METHOD__
            ));
        }

        // get element name and set it as identifier
        $elementId = $element->getAttribute('name');
        $element->setAttribute('id', $elementId);

        // get element options important in this view helper
        $elementEditor = $element->getEditor();
        $elementHeight = $element->getHeight();
        //$elementMaxHeight = $element->getMaxHeight();
        $elementAutoSize = $element->getAutosize();

        // bootstrap wysihtml5
        if ('bootstrap' === $elementEditor) {
            $render = parent::render($element);
            $render .= $this->getEditorScriptBootstrapWYSIHTML5($elementId);

            return $render;
        }

        // ckeditor
        if ('ckeditor' === $elementEditor) {
            $render = parent::render($element);
            $render .= $this->getEditorScriptCKEditor($elementId);

            return $render;
        }

        // tinymce
        if ('tinymce' === $elementEditor) {
            $render = parent::render($element);
            $render .= $this->getEditorScriptTinyMCE($elementId, $elementHeight, $elementAutoSize);

            return $render;
        }

        // no editor, create rows from height
        $rows = ceil($elementHeight / 25); // px to rows, let's say that 100px is 4 rows
        $element->setAttribute('rows', $rows);

        $render = parent::render($element);

        // autosize javascript works only on plain textarea
        if (true === $elementAutoSize) {
            $render .= $this->getAutosizeScript($elementId);
        }

        return $render;
    }

    /**
     * @param $elementId
     * @return string
     */
    private function getEditorScriptBootstrapWYSIHTML5($elementId)
    {
        $content = '$("#' . $elementId . '").wysihtml5({
            toolbar: {
                "font-styles": true, //Font styling, e.g. h1, h2, etc. Default true
                "emphasis": true, //Italics, bold, etc. Default true
                "lists": true, //(Un)ordered lists, e.g. Bullets, Numbers. Default true
                "html": false, //Button which allows you to edit the generated HTML. Default false
                "link": false, //Button to insert a link. Default true
                "image": false, //Button to insert an image. Default true,
                "color": false, //Button to change color of font  
                "blockquote": false, //Blockquote  
                "size": "none", //default: none, other options are xs, sm, lg,
                "fa": false //use font awesome instead of glyphicons? default false
            },
            events: {
        		"load": function() { 
                    // allows to resize wysihtml5 iframe
                    $(".wysihtml5-sandbox").css("resize", "vertical");
		        }
		    }
        });';
        return $this->getScript($content);
    }

    /**
     * @param $elementId
     * @return string
     */
    private function getEditorScriptCKEditor($elementId)
    {
        $content = 'CKEDITOR.replace("' . $elementId . '");';
        return $this->getScript($content);
    }

    /**
     * @param $elementId
     * @param int $elementHeight
     * @param bool $elementAutoSize
     * @return string
     */
    private function getEditorScriptTinyMCE($elementId, $elementHeight = 200, $elementAutoSize = false)
    {
        $options = '{
            selector: "#' . $elementId . '",
            height: ' . $elementHeight . '
        }';

        // todo: read more at https://www.tinymce.com/docs/plugins/autoresize/
        if (true === $elementAutoSize) {
            $options = '{
                selector: "#' . $elementId . '", 
                height: ' . $elementHeight . ',
                plugins: "autoresize",
                autoresize_bottom_margin: 20,
                autoresize_min_height: ' . $elementHeight . '
            }';
        }

        $content = 'tinymce.init(' . $options . ');';

        return $this->getScript($content);
    }

    /**
     * @param $elementId
     * @return string
     */
    private function getAutosizeScript($elementId)
    {
        $content = 'autosize($("#' . $elementId . '"));';
        return $this->getScript($content);
    }

    /**
     * Returns script tags with anonymous function waiting for document ready event.
     * @param $content
     * @return string
     */
    private function getScript($content)
    {
        $script = '<script>';
        $script .= '(function($){$(document).ready(function() {';
        $script .= $content;
        $script .= '});})(jQuery);';
        $script .= '</script>';

        return $script;
    }
}
