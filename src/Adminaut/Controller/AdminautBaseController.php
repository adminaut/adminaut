<?php

namespace Adminaut\Controller;

use Adminaut\Service\AccessControlService;
use Doctrine\ORM\EntityManager;
use Adminaut\Controller\Plugin\UserAuthentication;
use Zend\EventManager\EventManagerInterface;
use Zend\Http\PhpEnvironment\Request;
use Zend\Http\Response;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\I18n\Translator;
use Zend\Mvc\MvcEvent;

/**
 * Class AdminautBaseController
 * @package Adminaut\Controller
 * @method UserAuthentication userAuthentication()
 */
class AdminautBaseController extends AbstractActionController
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var AccessControlService
     */
    protected $acl;

    /**
     * @var array
     */
    protected $config;

    /**
     * @var Translator
     */
    protected $translator;

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
     * AdminautBaseController constructor.
     * @param $config
     * @param $acl
     * @param $em
     * @param $translator
     */
    public function __construct($config, $acl, $em, $translator)
    {
        $this->setConfig($config);
        $this->setAcl($acl);
        $this->setEntityManager($em);
        $this->setTranslator($translator);
    }

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
        $appearanceConfig = isset($this->config['adminaut']['appearance']) ? $this->config['adminaut']['appearance'] : [];
        $appearance = array_merge($this->defaultAppearance, $appearanceConfig);
        $this->layout()->setVariable('appearance', $appearance);

        parent::onDispatch($e);

        return $this;
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->em;
    }

    /**
     * @param EntityManager $em
     */
    public function setEntityManager($em)
    {
        $this->em = $em;
    }

    /**
     * @return AccessControlService
     */
    public function getAcl()
    {
        return $this->acl;
    }

    /**
     * @param AccessControlService $acl
     */
    public function setAcl($acl)
    {
        $this->acl = $acl;
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @param array $config
     */
    public function setConfig(array $config)
    {
        $this->config = $config;
    }

    /**
     * @param EventManagerInterface $events
     * @return $this
     */
    public function setEventManager(EventManagerInterface $events)
    {

        parent::setEventManager($events);
        /*
        $controller = $this;
        $events->attach('dispatch', function ($e) use ($controller) {
            $controller->layout('layout/admin');
            if ($controller->zfcUserAuthentication()->hasIdentity()) {
                var_dump($controller->zfcUserAuthentication()->getIdentity());
            }
        }, 100);
        */
        return $this;
    }

    /**
     * @return Translator
     */
    public function getTranslator()
    {
        return $this->translator;
    }

    /**
     * @param Translator $translator
     */
    public function setTranslator(Translator $translator)
    {
        $this->translator = $translator;
    }
}