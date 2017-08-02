<?php

namespace Adminaut\View\Helper;

use Adminaut\Manager\FileManager;
use Zend\View\Helper\AbstractHelper;
use Adminaut\Entity\File;
use Zend\View\Helper\Url;
use Zend\View\Renderer\PhpRenderer;

/**
 * Class FileManagerHelper
 * @package Adminaut\View\Helper
 */
class FileManagerHelper extends AbstractHelper
{

    /**
     * @var FileManager
     */
    private $fileManager;

    /**
     * FileManager constructor.
     * @param FileManager $fileManager
     */
    public function __construct(FileManager $fileManager)
    {
        $this->fileManager = $fileManager;
    }

    /**
     * @param $id
     * @return File
     */
    public function __invoke($id)
    {
        $file = $this->fileManager->getFileById($id);

        /** @var PhpRenderer $view */
        $view = $this->getView();

        /** @var Url $urlHelper */
        $urlHelper = $view->plugin('url');

        $fileUrl = $urlHelper('filesystem') . '/' . $file->getId();

        $file->setUrl($fileUrl);

        return $file;
    }
}
