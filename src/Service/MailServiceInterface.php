<?php

namespace Adminaut\Service;

/**
 * Interface MailServiceInterface
 * @package Adminaut\Service
 */
interface MailServiceInterface
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
    public function sendRecoveryMail($body, $toEmail, $toName = null);

    /**
     * @param $body
     * @param $toEmail
     * @param null $toName
     */
    public function sendNotificationMail($body, $toEmail, $toName = null);

    /**
     * @param $subject
     * @param $body
     * @param $fromEmail
     * @param $fromName
     * @param $toEmail
     * @param $toName
     */
    public function sendMail($subject, $body, $fromEmail, $fromName, $toEmail, $toName = null);
}
