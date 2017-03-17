<?php
namespace Adminaut\Authentication\Adapter;

use Zend\EventManager\Event;
use Zend\Http\Request;

/**
 * Class AdapterChainEvent
 * @package Adminaut\Authentication\Adapter
 */
class AdapterChainEvent extends Event
{
    /**
     * @return mixed
     */
    public function getIdentity()
    {
        return $this->getParam('identity');
    }

    /**
     * @param null $identity
     * @return AdapterChainEvent
     */
    public function setIdentity($identity = null)
    {
        if (null === $identity) {
            $this->setCode();
            $this->setMessages();
        }
        $this->setParam('identity', $identity);
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->getParam('code');
    }

    /**
     * @param null $code
     * @return AdapterChainEvent
     */
    public function setCode($code = null)
    {
        $this->setParam('code', $code);
        return $this;
    }

    /**
     * @return array
     */
    public function getMessages()
    {
        return $this->getParam('messages') ?: array();
    }

    /**
     * @param array $messages
     * @return AdapterChainEvent
     */
    public function setMessages($messages = array())
    {
        $this->setParam('messages', $messages);
        return $this;
    }

    /**
     * @return Request
     */
    public function getRequest()
    {
        return $this->getParam('request');
    }

    /**
     * @param Request $request
     * @return AdapterChainEvent
     */
    public function setRequest(Request $request)
    {
        $this->setParam('request', $request);
        $this->request = $request;
        return $this;
    }
}