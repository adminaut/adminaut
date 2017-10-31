<?php

namespace Adminaut\Service;

use Adminaut\Service\Exception\RuntimeException;
use Zend\Mail;

/**
 * Class MailService
 * @package Adminaut\Service
 */
class MailService implements MailServiceInterface
{
    /**
     * @var string
     */
    protected $systemName = 'Adminaut';

    /**
     * @var string
     */
    protected $systemEmail = 'no-reply@example.com';

    /**
     * MailService constructor.
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        if (array_key_exists('system_name', $options)) {
            $this->setSystemName($options['system_name']);
        }

        if (array_key_exists('system_email', $options)) {
            $this->setSystemEmail($options['system_email']);
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
     * @param $body
     * @param $toEmail
     * @param null $toName
     */
    public function sendInvitationMail($body, $toEmail, $toName = null)
    {
        $subject = sprintf('Invitation - %s', $this->getSystemName());
        $this->sendMail($subject, $body, $this->getSystemEmail(), $this->getSystemName(), $toEmail, $toName);
    }

    /**
     * @param $body
     * @param $toEmail
     * @param null $toName
     */
    public function sendPasswordRecoveryMail($body, $toEmail, $toName = null)
    {
        $subject = sprintf('Password recovery - %s', $this->getSystemName());
        $this->sendMail($subject, $body, $this->getSystemEmail(), $this->getSystemName(), $toEmail, $toName);
    }

    /**
     * @param $body
     * @param $toEmail
     * @param null $toName
     */
    public function sendNotificationMail($body, $toEmail, $toName = null)
    {
        $subject = sprintf('Notification - %s', $this->getSystemName());
        $this->sendMail($subject, $body, $this->getSystemEmail(), $this->getSystemName(), $toEmail, $toName);
    }

    /**
     * @param $subject
     * @param $body
     * @param $fromEmail
     * @param $fromName
     * @param $toEmail
     * @param null $toName
     */
    public function sendMail($subject, $body, $fromEmail, $fromName, $toEmail, $toName = null)
    {
        $mail = new Mail\Message();
        $mail->setEncoding('UTF-8'); // prevents invalid header runtime exception
        $mail->setSubject($subject);
        $mail->setBody($body);
        $mail->setFrom($fromEmail, $fromName);

        if (null === $toName) {
            $mail->addTo($toEmail);
        } else {
            $mail->addTo($toEmail, $toName);
        }

        $transport = new Mail\Transport\Sendmail();

        try {
            $transport->send($mail);
        } catch (\Exception $exception) {
            throw new RuntimeException('An exception has been thrown during email sending.', 1, $exception);
        }
    }
}
