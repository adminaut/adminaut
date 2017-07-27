<?php

namespace Adminaut\Datatype\File;

use Adminaut\Datatype\File;
use Adminaut\Datatype\FileImage;
use Adminaut\Manager\FileManager;
use Zend\Form\ElementInterface;
use Zend\Form\View\Helper\FormFile as ZendFormFile;

/**
 * Class FormViewHelper
 * @package Adminaut\Datatype\File
 */
class FormViewHelper extends ZendFormFile
{
    /**
     * @param ElementInterface|null $element
     * @return string
     */
    public function __invoke(ElementInterface $element = null)
    {
        return $this->render($element);
    }

    /**
     * @param ElementInterface $element
     * @return string
     */
    public function render(ElementInterface $element)
    {
        $element->setAttribute('type', 'file');
        $render = '<div class="datatype-file" data-attributes="'.htmlspecialchars(json_encode($element->getAttributes())).'">';
        if ($element instanceof File && $element->getFile()) {
            $fileObject = $element->getFile();
            $fm = FileManager::getInstance();
            $element->setAttribute('class', 'hidden');
            $render .= '<div class="file-icon">';
            if($element instanceof FileImage) {
                $render .= '<img src="'. $this->view->basePath($fm->getThumbImage($fileObject, 64, 64)) .'">';
            } else {
                $render .= '<img src="/img/adminaut/file-icons/' . $fileObject->getFileExtension() . '.svg">';
            }
            $render .= '</div>';
            $render .= '<div class="file-info">';
            $render .= '    <strong>'.$fileObject->getName().'</strong><br>';
            $render .= '    <span class="file-size">'.$fileObject->getFormattedSize().'</span>';
            $render .= '    <button type="button" class="btn btn-xs btn-danger file-remove"><i class="fa fa-remove"></i> Remove</button>';
            $render .= '</div>';
        } else {
            $render .= parent::render($element);
        }
        $render .= '</div>';
        $render .= '<script>appendStyle("'. $this->getView()->basepath('adminaut/css/datatype/file.css') .'")</script>';
        $render .= '<script>appendScript("'. $this->getView()->basepath('adminaut/js/datatype/file.js') .'")</script>';

        return $render;
    }
}