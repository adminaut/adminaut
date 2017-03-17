<?php

namespace Adminaut\Authentication\Adapter;

/**
 * Interface ChainableAdapter
 * @package Adminaut\Authentication\Adapter
 */
interface ChainableAdapter
{
    /**
     * @param AdapterChainEvent $e
     * @return mixed
     */
    public function authenticate(AdapterChainEvent $e);
}