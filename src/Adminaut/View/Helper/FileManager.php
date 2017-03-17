<?php

namespace Adminaut\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Adminaut\Entity\File;

/**
 * Class FileManager
 * @package Adminaut\View\Helper
 */
class FileManager extends AbstractHelper
{
    /**
     * @var
     */
    protected $service;

    /**
     * @var array $params
     */
    protected $params;

    /**
     * @param $id
     * @return File
     */
    public function __invoke($id)
    {
        $file = $this->getService()->getFileById($id);
        $file = $this->generateDynamicParameters($file);
        return $file;
    }

    /**
     * @param File $file
     * @return File
     */
    private function generateDynamicParameters(File $file)
    {
        $urlHelper = $this->getView()->plugin('url');
        $file->setUrl(
            $urlHelper('filesystem') . '/' . $file->getId()
        );
        return $file;
    }

    /**
     * @return mixed
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * @param $service
     * @return FileManager
     */
    public function setService($service)
    {
        $this->service = $service;
        return $this;
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @param array $params
     * @return $this
     */
    public function setParams(array $params)
    {
        $this->params = $params;
        return $this;
    }
}