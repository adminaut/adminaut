<?php

namespace Adminaut\Service;

use Adminaut\Entity\UserEntityInterface;
use Adminaut\Service\Exception\RuntimeException;
use MassimoFilippi\MailModule\Adapter\AdapterInterface;
use MassimoFilippi\MailModule\Service\MailService as MfccMailService;
use MassimoFilippi\MailModule\Model\Message\Message;
use MassimoFilippi\MailModule\Model\Recipient\Recipient;
use MassimoFilippi\MailModule\Model\Sender\Sender;
use Zend\I18n\Translator\Translator;
use Zend\I18n\Translator\TranslatorInterface;
use Zend\Mail;
use Zend\View\Model\ViewModel;
use Zend\View\Renderer\RendererInterface;

/**
 * Class MailService
 * @package Adminaut\Service
 */
class MailService extends MfccMailService implements MailServiceInterface
{
    const ACCOUNT_INFORMATION_OPERATION_CREATE = 'create';
    const ACCOUNT_INFORMATION_OPERATION_UPDATE = 'update';

    /**
     * @var string
     */
    protected $systemName = 'Adminaut';

    /**
     * @var string
     */
    protected $systemEmail = 'no-reply@example.com';

    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * @var RendererInterface
     */
    protected $viewRenderer;

    /**
     * @var array
     */
    protected $templates = [
        'account_information' => 'adminaut/email/account-information.phtml',
        'notification' => 'adminaut/email/notification.phtml',
        'password_recovery' => 'adminaut/email/password-recovery.phtml',
    ];

    /**
     * MailService constructor.
     * @param array $options
     */
    public function __construct(AdapterInterface $adapter, array $options = [], TranslatorInterface $translator, RendererInterface $renderer)
    {
        parent::__construct($adapter);
        $this->translator = $translator;
        $this->viewRenderer = $renderer;

        if (array_key_exists('system_name', $options)) {
            $this->setSystemName($options['system_name']);
        }

        if (array_key_exists('system_email', $options)) {
            $this->setSystemEmail($options['system_email']);
        }

        if(array_key_exists('templates', $options)) {
            $this->templates = array_merge($this->templates, $options['templates']);
        }
    }

    /**
     * @return string
     */
    public function getSystemName()
    {
        return $this->systemName;
    }

    /**
     * @param string $systemName
     */
    public function setSystemName($systemName)
    {
        $this->systemName = (string)$systemName;
    }

    /**
     * @return string
     */
    public function getSystemEmail()
    {
        return $this->systemEmail;
    }

    /**
     * @param string $systemEmail
     */
    public function setSystemEmail($systemEmail)
    {
        $this->systemEmail = (string)$systemEmail;
    }

    /**
     * @param UserEntityInterface $user
     * @param string $rawPassword
     * @param string $operation
     */
    public function sendAccountInformation(UserEntityInterface $user, string $rawPassword, string $operation = self::ACCOUNT_INFORMATION_OPERATION_CREATE)
    {
        $subject = sprintf($this->translator->translate('Account Information - %s'), $this->getSystemName());
        $template = $this->templates['account_information'];

        $viewModel = new ViewModel();
        $viewModel->setTemplate($template);
        $viewModel->setVariables([
            'user' => $user,
            'operation' => $operation,
            'raw_password' => $rawPassword,
            'system_name' => $this->systemName
        ]);
        $body = $this->viewRenderer->render($viewModel);

        $sender = new Sender($this->systemEmail, $this->systemName);
        $recipient = new Recipient($user->getEmail());
        $recipient->setName($user->getName());

        $message = new Message($sender, $recipient);
        $message->setSubject($subject);
        $message->setMessage($body);

        $this->sendMail($message);
    }

    public function sendInvitationMail($body, $toEmail, $toName = null)
    {
        // TODO: Implement sendInvitationMail() method.
    }

    /**
     * @param $recoveryKey
     * @param $toEmail
     * @param string|null $toName
     */
    public function sendPasswordRecoveryMail($recoveryKey, $toEmail, $toName = null)
    {
        $subject = sprintf($this->translator->translate('Password recovery - %s'), $this->getSystemName());
        $template = $this->templates['password_recovery'];

        $viewModel = new ViewModel();
        $viewModel->setTemplate($template);
        $viewModel->setVariables([
            'email' => $toEmail,
            'recoveryKey' => $recoveryKey,
            'system_name' => $this->systemName
        ]);
        $body = $this->viewRenderer->render($viewModel);

        $sender = new Sender($this->systemEmail, $this->systemName);
        $recipient = new Recipient($toEmail);
        $recipient->setName($toName);

        $message = new Message($sender, $recipient);
        $message->setSubject($subject);
        $message->setMessage($body);

        $this->sendMail($message);
    }

    /**
     * @param $body
     * @param $toEmail
     * @param null $toName
     */
    public function sendNotificationMail($body, $toEmail, $toName = null)
    {
        $subject = sprintf($this->translator->translate('Notification - %s'), $this->getSystemName());
        $template = $this->templates['notification'];

        $viewModel = new ViewModel();
        $viewModel->setTemplate($template);
        $viewModel->setVariables([
            'message' => $body
        ]);
        $body = $this->viewRenderer->render($viewModel);

        $sender = new Sender($this->systemEmail, $this->systemName);
        $recipient = new Recipient($toEmail, $toName);

        $message = new Message($sender, $recipient);
        $message->setSubject($subject);
        $message->setMessage($body);

        $this->sendMail($message);
    }
}
