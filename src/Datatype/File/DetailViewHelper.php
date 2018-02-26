<?php

namespace Adminaut\Datatype\File;

use Adminaut\Datatype\DatatypeHelperTrait;
use Adminaut\Datatype\File;
use Adminaut\Datatype\FileImage;
use Adminaut\Manager\FileManager;
use Zend\Form\ElementInterface;
use Zend\Form\View\Helper\FormFile as ZendFormFile;

/**
 * Class DetailViewHelper
 * @package Adminaut\Datatype\File
 */
class DetailViewHelper extends ZendFormFile
{
    use DatatypeHelperTrait;

    /**
     * @var FileManager
     */
    private $fileManager;

    /**
     * DetailViewHElper constructor.
     * @param FileManager $fileManager
     */
    public function __construct(FileManager $fileManager)
    {
        $this->fileManager = $fileManager;
    }

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
        if ($element instanceof File && $element->getFile()) {
            $render = '<div class="datatype-file">';
            $fileObject = $element->getFile();
            $element->setAttribute('class', 'hidden');
            $render .= '<div class="file-icon">';
            if ($element instanceof FileImage) {
                $render .= '<img src="' . $this->view->adminautImage($fileObject, 64, 64, 'crop') . '" width="64" height="64" class="datatype-file-image-preview">';
            } else {
                $render .= '<img src="/img/adminaut/file-icons/' . $fileObject->getFileExtension() . '.svg">';
            }
            $render .= '</div>';
            $render .= '<div class="file-info">';
            $render .= '    <strong>' . $fileObject->getName() . '</strong><br>';
            $render .= '    <span class="file-size">' . $fileObject->getFormattedSize() . '</span>';
            $render .= '</div>';
            $render .= '</div>';
        } else {
            $render = "";
        }

        $this->appendStylesheet('adminaut/css/datatype/file.css');
        $this->appendScript('adminaut/js/datatype/file.js');

        return $render;
    }
}
