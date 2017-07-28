<?php

namespace Adminaut\Controller;

use Adminaut\Controller\Plugin\AclPlugin;
use Adminaut\Controller\Plugin\ConfigPlugin;
use Adminaut\Controller\Plugin\TranslatorPlugin;
use Adminaut\Controller\Plugin\AuthenticationPlugin;
use Adminaut\Service\AccessControlService;
use Zend\Http\PhpEnvironment\Request;
use Zend\Http\Response;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Plugin\FlashMessenger\FlashMessenger;

/**
 * Class AdminautBaseController
 * @package Adminaut\Controller
 * @method AuthenticationPlugin userAuthentication()
 * @method AclPlugin acl()
 * @method ConfigPlugin config()
 * @method TranslatorPlugin translator()
 * @method FlashMessenger flashMessenger()
 */
class AdminautBaseController extends AbstractActionController
{

    /**
     * @var array
     */
    private $defaultAppearance = [
        'skin' => 'blue',
        'title' => 'Adminaut',
        'logo' => [
            'type' => 'image',
            'large' => 'adminaut/img/adminaut-logo.svg',
            'small' => 'adminaut/img/adminaut-logo-mini.svg',
        ],
        'footer' => '',
    ];

    /**
     * @param MvcEvent $e
     * @return AdminautBaseController|Response
     */
    public function onDispatch(MvcEvent $e)
    {
        if (!$this->userAuthentication()->hasIdentity()) {

            /** @var Request $request */
            $request = $this->getRequest();

            return $this->redirect()->toRoute(AuthController::ROUTE_LOGIN, [], [
                'query' => [
                    'redirect' => rawurlencode($request->getUriString()),
                ],
            ]);
        }
        $acl = $this->getAcl();
        $acl->setUser($this->userAuthentication()->getIdentity());

        $this->layout('layout/admin');
        $this->layout()->setVariable('acl', $acl);
        $appearanceConfig = isset($this->config['adminaut']['appearance']) ? $this->config()['adminaut']['appearance'] : [];
        $appearance = array_merge($this->defaultAppearance, $appearanceConfig);
        $this->layout()->setVariable('appearance', $appearance);

        parent::onDispatch($e);

        return $this;
    }

    /**
     * @deprecated User $this->acl() instead.
     * @return AccessControlService
     */
    public function getAcl()
    {
        return $this->acl()->getAcl();
    }

    /**
     * @deprecated User $this->config() instead.
     * @return ConfigPlugin
     */
    public function getConfig()
    {
        return $this->config();
    }

    /**
     * @deprecated User $this->translator() instead.
     * @return TranslatorPlugin
     */
    public function getTranslator()
    {
        return $this->translator();
    }
}
