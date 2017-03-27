<?php

namespace Adminaut\Controller;

use Adminaut\Service\AccessControlService;
use Doctrine\ORM\EntityManager;
use Adminaut\Controller\Plugin\UserAuthentication;
use Zend\EventManager\EventManagerInterface;
use Zend\Mvc\Controller\AbstractActionController;
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

    public function __construct($acl, $em)
    {
        $this->setAcl($acl);
        $this->setEntityManager($em);
    }

    /**
     * @param MvcEvent $e
     * @return $this
     */
    public function onDispatch(MvcEvent $e)
    {
        parent::onDispatch($e);
        if (!$this->userAuthentication()->hasIdentity()) {
            return $this->redirect()->toRoute('adminaut-user/login');
        }
        $acl = $this->getAcl();
        $acl->setUser($this->userAuthentication()->getIdentity());

        $this->layout('layout/admin');
        $this->layout()->setVariable('acl', $acl);
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
}