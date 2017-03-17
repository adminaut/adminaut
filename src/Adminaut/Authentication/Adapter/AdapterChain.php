<?php
namespace Adminaut\Authentication\Adapter;

use Adminaut\EventManager\EventProvider;
use Adminaut\Exception;

use Zend\Authentication\Adapter\AdapterInterface;
use Zend\Authentication\Result as AuthenticationResult;
use Zend\EventManager\Event;
use Zend\Stdlib\RequestInterface as Request;
use Zend\Stdlib\ResponseInterface as Response;

/**
 * Class AdapterChain
 * @package Adminaut\Authentication\Adapter
 */
class AdapterChain extends EventProvider implements AdapterInterface
{
    /**
     * @var AdapterChainEvent
     */
    protected $event;

    /**
     * @return AuthenticationResult
     */
    public function authenticate()
    {
        $e = $this->getEvent();
        $result = new AuthenticationResult(
            $e->getCode(),
            $e->getIdentity(),
            $e->getMessages()
        );
        $this->resetAdapters();
        return $result;
    }

    /**
     * @param Request $request
     * @return bool|Response
     */
    public function prepareForAuthentication(Request $request)
    {
        $e = $this->getEvent();
        $e->setRequest($request);
        $this->getEventManager()->trigger('authenticate.pre', $e);
        $result = $this->getEventManager()->trigger('authenticate', $e, function ($test) {
            return ($test instanceof Response);
        });
        if ($result->stopped()) {
            if ($result->last() instanceof Response) {
                return $result->last();
            }
            throw new Exception\AuthenticationEventException(
                sprintf(
                    'Auth event was stopped without a response. Got "%s" instead',
                    is_object($result->last()) ? get_class($result->last()) : gettype($result->last())
                )
            );
        }
        if ($e->getIdentity()) {
            $this->getEventManager()->trigger('authenticate.success', $e);
            return true;
        }
        $this->getEventManager()->trigger('authenticate.fail', $e);
        return false;
    }

    /**
     * @return AdapterChain
     */
    public function resetAdapters()
    {
        $listeners = $this->getEventManager()->getListeners('authenticate');
        foreach ($listeners as $listener) {
            $listener = $listener->getCallback();
            if (is_array($listener) && $listener[0] instanceof ChainableAdapter) {
                $listener[0]->getStorage()->clear();
            }
        }
        return $this;
    }

    /**
     * @return void
     */
    public function logoutAdapters()
    {
        $this->getEventManager()->trigger('logout', $this->getEvent());
    }

    /**
     * @return AdapterChainEvent
     */
    public function getEvent()
    {
        if (null === $this->event) {
            $this->setEvent(new AdapterChainEvent);
            $this->event->setTarget($this);
        }
        return $this->event;
    }

    /**
     * @param Event $e
     * @return AdapterChain
     */
    public function setEvent(Event $e)
    {
        if (!$e instanceof AdapterChainEvent) {
            $eventParams = $e->getParams();
            $e = new AdapterChainEvent();
            $e->setParams($eventParams);
        }
        $this->event = $e;
        return $this;
    }
}