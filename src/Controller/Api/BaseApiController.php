<?php
namespace Adminaut\Controller\Api;

use Zend\I18n\Translator\TranslatorInterface;
use Zend\Mvc\Controller\AbstractRestfulController;
use Adminaut\Controller\Plugin\AuthenticationPlugin;
use Zend\Mvc\MvcEvent;
use Zend\View\Model\JsonModel;

/**
 * Class BaseApiController
 * @method AuthenticationPlugin authentication()
 */
class BaseApiController extends AbstractRestfulController
{
    /**
     * @return array
     */
    protected function hasIdentity()
    {
        return $this->authentication()->hasIdentity();
    }

    /**
     * @return array
     */
    protected function returnForbidden()
    {
        $this->response->setStatusCode(403);

        return new JsonModel([
            'content' => '403 Forbidden'
        ]);
    }

    /**
     * @param MvcEvent $e
     * @return mixed|Response
     */
    public function onDispatch(MvcEvent $e)
    {
        $serviceLocator = $e->getApplication()->getServiceManager();

        if ($this->authentication()->hasIdentity()) {
            /** @var UserEntityInterface $userEntity */
            $userEntity = $this->authentication()->getIdentity();

            if(!empty($userEntity->getLanguage())) {
                /** @var TranslatorInterface|null $translator */
                $translator = null;
                if ($serviceLocator->has('MvcTranslator')) {
                    $translator = $serviceLocator->get('MvcTranslator');
                } elseif ($serviceLocator->has(TranslatorInterface::class)) {
                    $translator = $serviceLocator->get(TranslatorInterface::class);
                } elseif ($serviceLocator->has('Translator')) {
                    $translator = $serviceLocator->get('Translator');
                }

                if ($translator instanceof TranslatorInterface) {
                    $translator->setLocale('en_US');
                    $translator->setFallbackLocale('en_US');

                    switch ($userEntity->getLanguage()) {
                        case 'en': $translator->setLocale('en_US'); break;
                        case 'de': $translator->setLocale('de_DE'); break;
                        case 'cs': $translator->setLocale('cs_CZ'); break;
                        case 'sk': $translator->setLocale('sk_SK'); break;
                    }
                }
            }
        }

        return parent::onDispatch($e);
    }
}