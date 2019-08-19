<?php
namespace Adminaut\Controller\Api;

use Zend\Mvc\Controller\AbstractRestfulController;
use Adminaut\Controller\Plugin\AuthenticationPlugin;
use Zend\View\Model\JsonModel;

/**
 * Class BaseApiController
 * @method AuthenticationPlugin authentication()
 */
class BaseApiController extends AbstractRestfulController
{
    /**
     * @return array
     */
    protected function hasIdentity()
    {
        return $this->authentication()->hasIdentity();
    }

    /**
     * @return array
     */
    protected function returnForbidden()
    {
        $this->response->setStatusCode(403);

        return new JsonModel([
            'content' => '403 Forbidden'
        ]);
    }
}