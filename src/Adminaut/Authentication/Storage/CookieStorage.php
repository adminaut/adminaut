<?php

namespace Adminaut\Authentication\Storage;

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
     * Default cookie name
     */
    const COOKIE_NAME = 'access-token';

    /**
     * Default cookie secure
     */
    const COOKIE_SECURE = true;

    /**
     * Default cookie httpOnly
     */
    const COOKIE_HTTP_ONLY = true;

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
     * @var string
     */
    protected $cookieName;

    /**
     * @var boolean
     */
    protected $cookieSecure;

    /**
     * @var boolean
     */
    protected $cookieHttpOnly;

    //-------------------------------------------------------------------------

    /**
     * CookieStorage constructor.
     * @param Request $request
     * @param Response $response
     * @param array $cookieOptions
     */
    public function __construct(Request $request, Response $response, array $cookieOptions = [])
    {
        $this->request = $request;
        $this->response = $response;

        $options = [
            'name' => self::COOKIE_NAME,
            'secure' => self::COOKIE_SECURE,
            'httpOnly' => self::COOKIE_HTTP_ONLY,
        ];

        $options = array_merge($options, $cookieOptions);

        $this->cookieName = $options['name'];
        $this->cookieSecure = $options['secure'];
        $this->cookieHttpOnly = $options['httpOnly'];
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

        if ($cookieHeaders && $cookieHeaders->offsetExists($this->cookieName)) {
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
        return $this->isEmpty() ? null : $this->request->getCookie()->offsetGet($this->cookieName);
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
        $cookie->setName($this->cookieName);
        $cookie->setValue($contents);
        $cookie->setPath('/');
        $cookie->setSecure($this->cookieSecure);
        $cookie->setHttponly($this->cookieHttpOnly);
        $cookie->setExpires(new \DateTime('+1 month'));

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
        if (!$this->isEmpty()) {
            $cookie = new SetCookie();
            $cookie->setName($this->cookieName);
            $cookie->setValue(null);
            $cookie->setSecure($this->cookieSecure);
            $cookie->setHttponly($this->cookieHttpOnly);
            $cookie->setExpires(0);

            $responseHeaders = $this->response->getHeaders();
            $responseHeaders->addHeader($cookie);
        }
    }
}
