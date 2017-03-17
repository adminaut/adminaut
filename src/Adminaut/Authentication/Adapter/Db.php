<?php
namespace Adminaut\Authentication\Adapter;

use Adminaut\Authentication\Adapter\AdapterChainEvent as AuthEvent;
use Adminaut\Entity\UserEntity;
use Adminaut\Mapper\UserMapper;
use Adminaut\Options\UserOptions;

use Zend\Authentication\Result as AuthenticationResult;
use Zend\ServiceManager\ServiceManager;
use Zend\Crypt\Password\Bcrypt;
use Zend\Session\Container as SessionContainer;

/**
 * Class Db
 * @package Adminaut\Authentication\Adapter
 */
class Db extends AbstractAdapter
{
    /**
     * @var UserMapper
     */
    protected $userMapper;

    /**
     * @var
     */
    protected $credentialPreprocessor;

    /**
     * @var ServiceManager
     */
    protected $serviceManager;

    /**
     * @var UserOptions
     */
    protected $userOptions;


    public function __construct($userMapper, $userOptions)
    {
        $this->setUserMapper($userMapper);
        $this->setUserOptions($userOptions);
    }

    /**
     * @param AdapterChainEvent $e
     */
    public function logout(AuthEvent $e)
    {
        $this->getStorage()->clear();
    }

    /**
     * @param AdapterChainEvent $e
     * @return bool
     */
    public function authenticate(AuthEvent $e)
    {
        if ($this->isSatisfied()) {
            $storage = $this->getStorage()->read();
            $e->setIdentity($storage['identity'])
                ->setCode(AuthenticationResult::SUCCESS)
                ->setMessages(array('Authentication successful.'));
            return true;
        }


        $identity = $e->getRequest()->getPost()->get('identity');
        $credential = $e->getRequest()->getPost()->get('credential');
        $credential = $this->preProcessCredential($credential);
        /** @var UserEntity|null $userEntity */
        $userEntity = null;

        $fields = $this->getUserOptions()->getAuthIdentityFields();
        while (!is_object($userEntity) && count($fields) > 0) {
            $mode = array_shift($fields);
            switch ($mode) {
                case 'username':
                    $userEntity = $this->getUserMapper()->findByUsername($identity);
                    break;
                case 'email':
                    $userEntity = $this->getUserMapper()->findByEmail($identity);
                    break;
            }
        }

        if (!$userEntity) {
            $e->setCode(AuthenticationResult::FAILURE_IDENTITY_NOT_FOUND)
                ->setMessages(array('A record with the supplied identity could not be found.'));
            $this->setSatisfied(false);
            return false;
        }

        if ($this->getUserOptions()->isEnableUserStatus()) {
            if (!in_array($userEntity->getStatus(), $this->getUserOptions()->getAllowedLoginStatus())) {
                $e->setCode(AuthenticationResult::FAILURE_UNCATEGORIZED)
                  ->setMessages(array('A record with the supplied identity is not active.'));
                $this->setSatisfied(false);
                return false;
            }
        }

        $bcrypt = new Bcrypt();
        $bcrypt->setCost($this->getUserOptions()->getPasswordCost());
        if (!$bcrypt->verify($credential, $userEntity->getPassword())) {
            $e->setCode(AuthenticationResult::FAILURE_CREDENTIAL_INVALID)
                ->setMessages(array('Supplied credential is invalid.'));
            $this->setSatisfied(false);
            return false;
        }

        $session = new SessionContainer($this->getStorage()->getNameSpace());
        $session->getManager()->regenerateId();

        $e->setIdentity($userEntity->getId());

        $this->updateUserPasswordHash($userEntity, $credential, $bcrypt);
        $this->setSatisfied(true);
        $storage = $this->getStorage()->read();
        $storage['identity'] = $e->getIdentity();
        $this->getStorage()->write($storage);
        $e->setCode(AuthenticationResult::SUCCESS)
            ->setMessages(array('Authentication successful.'));
        return true;
    }

    /**
     * @param UserEntity $userObject
     * @param $password
     * @param Bcrypt $bcrypt
     * @return Db|bool
     */
    protected function updateUserPasswordHash(UserEntity $userObject, $password, Bcrypt $bcrypt)
    {
        $hash = explode('$', $userObject->getPassword());
        if ($hash[2] === $bcrypt->getCost()) {
            return true;
        }
        $userObject->setPassword($bcrypt->create($password));
        $this->getUserMapper()->update($userObject);
        return $this;
    }

    /**
     * @param $credential
     * @return mixed
     */
    public function preprocessCredential($credential)
    {
        $processor = $this->getCredentialPreprocessor();
        if (is_callable($processor)) {
            return $processor($credential);
        }
        return $credential;
    }

    /**
     * @return UserMapper
     */
    public function getUserMapper(): UserMapper
    {
        return $this->userMapper;
    }

    /**
     * @param UserMapper $userMapper
     */
    public function setUserMapper(UserMapper $userMapper)
    {
        $this->userMapper = $userMapper;
    }

    /**
     * @return mixed
     */
    public function getCredentialPreprocessor()
    {
        return $this->credentialPreprocessor;
    }

    /**
     * @param $credentialPreprocessor
     * @return $this
     */
    public function setCredentialPreprocessor($credentialPreprocessor)
    {
        $this->credentialPreprocessor = $credentialPreprocessor;
        return $this;
    }

    /**
     * @return UserOptions
     */
    public function getUserOptions()
    {
        return $this->userOptions;
    }

    /**
     * @param UserOptions $userOptions
     */
    public function setUserOptions(UserOptions $userOptions)
    {
        $this->userOptions = $userOptions;
    }
}