<?php

namespace Adminaut\Form\View\Helper;

use Adminaut\Datatype\File;
use Adminaut\Form\Element\FileImage;
use Adminaut\Manager\FileManager;
use Zend\Form\ElementInterface;
use Zend\Form\View\Helper\FormFile as ZendFormFile;

/**
 * Class FormFile
 * @package Adminaut\Form\View\Helper
 */
class FormFile extends ZendFormFile
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
        if ($element instanceof File && $element->getFile()) {
            $fileObject = $element->getFile();
            $fm = FileManager::getInstance();
            $element->setAttribute('class', 'hidden');
            $render = parent::render($element);
            $render .= '<div class="file-container"><div class="file-icon">';
            if($element instanceof FileImage) {
                $render .= '<img src="'. $this->view->basePath($fm->getThumbImage($fileObject, 64, 64)) .'">';
            } else {
                $render .= '<img src="/img/adminaut/file-icons/' . $fileObject->getFileExtension() . '.svg">';
            }
            $render .= '</div><div class="file-info"><strong>'.$fileObject->getName().'</strong><br><span class="file-size">'.$fileObject->getFormattedSize().'</span><button type="button" class="btn btn-xs btn-danger file-remove"><i class="fa fa-remove"></i> Remove</button></div></div>';
        } else {
            $render = parent::render($element);
        }

        return $render;
    }
}