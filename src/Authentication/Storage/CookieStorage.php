<?php

namespace Adminaut\Authentication\Storage;

use Zend\Authentication\Storage\StorageInterface;
use Zend\Http\Header\SetCookie;
use Zend\Http\PhpEnvironment\Request;
use Zend\Http\PhpEnvironment\Response;

/**
 * Class CookieStorage
 * @package Adminaut\Authentication\Storage
 */
class CookieStorage implements StorageInterface
{

    /**
     * Request dependency - so we can read cookies from request headers
     * @var Request
     */
    protected $request;

    /**
     * Response dependency - so we can write/delete cookies in response headers
     * @var Response
     */
    protected $response;

    /**
     * Options dependency - so we can have some options :)
     * @var CookieStorageOptions
     */
    protected $options;

    //-------------------------------------------------------------------------

    /**
     * CookieStorage constructor.
     * @param Request $request
     * @param Response $response
     * @param CookieStorageOptions $options
     */
    public function __construct(Request $request, Response $response, CookieStorageOptions $options)
    {
        $this->request = $request;
        $this->response = $response;
        $this->options = $options;
    }

    //-------------------------------------------------------------------------

    /**
     * Returns true if and only if storage is empty
     *
     * @throws \Zend\Authentication\Exception\ExceptionInterface If it is impossible to determine whether storage is empty
     * @return bool
     */
    public function isEmpty()
    {
        $cookieHeaders = $this->request->getCookie();

        if ($cookieHeaders && $cookieHeaders->offsetExists($this->options->getCookieName())) {
            return false;
        }
        return true;
    }

    /**
     * Returns the contents of storage
     *
     * Behavior is undefined when storage is empty.
     *
     * @throws \Zend\Authentication\Exception\ExceptionInterface If reading contents from storage is impossible
     * @return mixed
     */
    public function read()
    {
        return $this->isEmpty() ? null : $this->request->getCookie()->offsetGet($this->options->getCookieName());
    }

    /**
     * Writes $contents to storage
     *
     * @param  mixed $contents
     * @throws \Zend\Authentication\Exception\ExceptionInterface If writing $contents to storage is impossible
     * @return void
     */
    public function write($contents)
    {
        $cookie = new SetCookie();
        $cookie->setName($this->options->getCookieName());
        $cookie->setPath($this->options->getCookiePath());
        $cookie->setDomain($this->options->getCookieDomain());
        $cookie->setSecure($this->options->isCookieSecure());
        $cookie->setHttponly($this->options->isCookieHttpOnly());
        $cookie->setExpires($this->options->getCookieExpires());

        $cookie->setValue($contents);

        $responseHeaders = $this->response->getHeaders();
        $responseHeaders->addHeader($cookie);
    }

    /**
     * Clears contents from storage
     *
     * @throws \Zend\Authentication\Exception\ExceptionInterface If clearing contents from storage is impossible
     * @return void
     */
    public function clear()
    {
        if (false === $this->isEmpty()) {
            $cookie = new SetCookie();
            $cookie->setName($this->options->getCookieName());
            $cookie->setPath($this->options->getCookiePath());
            $cookie->setDomain($this->options->getCookieDomain());
            $cookie->setSecure($this->options->isCookieSecure());
            $cookie->setHttponly($this->options->isCookieHttpOnly());
            $cookie->setExpires(0);

            $cookie->setValue(null);

            $responseHeaders = $this->response->getHeaders();
            $responseHeaders->addHeader($cookie);
        }
    }
}
