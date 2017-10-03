<?php

namespace Adminaut\Controller;

use Adminaut\Controller\Plugin\ConfigPlugin;
use Adminaut\Controller\Plugin\AuthenticationPlugin;
use Adminaut\Controller\Plugin\IsAllowedPlugin;
use Adminaut\Controller\Plugin\TranslatePlugin;
use Adminaut\Controller\Plugin\TranslatePluralPlugin;
use Zend\Http\PhpEnvironment\Request;
use Zend\Http\Response;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Plugin\FlashMessenger\FlashMessenger;

/**
 * Class AdminautBaseController
 * @package Adminaut\Controller
 * @method AuthenticationPlugin authentication()
 * @method ConfigPlugin config()
 * @method IsAllowedPlugin isAllowed($module = null, $permissionLevel = null, $element = null, $entity = null)
 * @method TranslatePlugin translate($message, $textDomain = 'default', $locale = null)
 * @method TranslatePluralPlugin translatePlural($singular, $plural, $number, $textDomain = 'default', $locale = null)
 * @method FlashMessenger flashMessenger()
 */
class AdminautBaseController extends AbstractActionController
{

    /**
     * @param $message
     */
    protected function addInfoMessage($message)
    {
        $this->flashMessenger()->addInfoMessage($message);
    }

    /**
     * @param $message
     */
    protected function addSuccessMessage($message)
    {
        $this->flashMessenger()->addSuccessMessage($message);
    }

    /**
     * @param $message
     */
    protected function addWarningMessage($message)
    {
        $this->flashMessenger()->addWarningMessage($message);
    }

    /**
     * @param $message
     */
    protected function addErrorMessage($message = null)
    {
        if (null === $message) {
            $message = $this->translate('Wild application error occurred.');
        }
        $this->flashMessenger()->addErrorMessage($message);
    }

    /**
     * @param MvcEvent $e
     * @return mixed|Response
     */
    public function onDispatch(MvcEvent $e)
    {
        if (!$this->authentication()->hasIdentity()) {

            /** @var Request $request */
            $request = $this->getRequest();

            return $this->redirect()->toRoute(AuthController::ROUTE_LOGIN, [], [
                'query' => [
                    'redirect' => rawurlencode($request->getUriString()),
                ],
            ]);
        }

        $this->layout('layout/admin');

        return parent::onDispatch($e);
    }
}
