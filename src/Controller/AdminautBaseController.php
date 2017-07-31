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
     * @param MvcEvent $e
     * @return AdminautBaseController|Response
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
        $acl = $this->isAllowed()->getAccessControlService();
        $acl->setUser($this->authentication()->getIdentity());

        $this->layout('layout/admin');
        $this->layout()->setVariable('acl', $acl);
        $this->layout()->setVariable('appearance', $this->getAppearance());

        parent::onDispatch($e);

        return $this;
    }

    /**
     * @return array
     */
    private function getAppearance()
    {
        $config = $this->config();

        $appearanceDefault = [
            'skin' => 'blue',
            'title' => 'Adminaut',
            'logo' => [
                'type' => 'image',
                'large' => 'adminaut/img/adminaut-logo.svg',
                'small' => 'adminaut/img/adminaut-logo-mini.svg',
            ],
            'footer' => '',
        ];

        $appearanceConfig = isset($config['adminaut']['appearance']) ? $config['adminaut']['appearance'] : [];

        return array_merge($appearanceDefault, $appearanceConfig);
    }
}
