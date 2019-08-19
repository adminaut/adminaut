<?php

namespace Adminaut\Service;

use \MassimoFilippi\MailModule\Service\MailServiceInterface as MfccMailServiceInterface;

/**
 * Interface MailServiceInterface
 * @package Adminaut\Service
 */
interface MailServiceInterface extends MfccMailServiceInterface
{
    /**
     * @param $body
     * @param $toEmail
     * @param null $toName
     */
    public function sendInvitationMail($body, $toEmail, $toName = null);

    /**
     * @param $body
     * @param $toEmail
     * @param null $toName
     */
    public function sendPasswordRecoveryMail($body, $toEmail, $toName = null);

    /**
     * @param $body
     * @param $toEmail
     * @param null $toName
     */
    public function sendNotificationMail($body, $toEmail, $toName = null);
}
