<?php

namespace Webkul\Admin\Helpers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;

class CompanySmtpMailer
{
    /**
     * Apply the company-specific SMTP configuration at runtime.
     *
     * Reads SMTP settings from `core_config` (scoped by company)
     * and overrides Laravel's mail config. Falls back to .env
     * values when no company-level setting exists.
     */
    public static function apply(): void
    {
        $host       = core()->getConfigData('email.smtp.account.host');
        $port       = core()->getConfigData('email.smtp.account.port');
        $encryption = core()->getConfigData('email.smtp.account.encryption');
        $username   = core()->getConfigData('email.smtp.account.username');
        $password   = core()->getConfigData('email.smtp.account.password');
        $senderName = core()->getConfigData('email.smtp.account.sender_name');
        $senderEmail = core()->getConfigData('email.smtp.account.sender_email');

        // Only override if at least host and username are set
        if (! empty($host) && ! empty($username)) {
            Config::set('mail.default', 'smtp');
            Config::set('mail.mailers.smtp.host', $host);
            Config::set('mail.mailers.smtp.port', $port ?: 587);
            Config::set('mail.mailers.smtp.encryption', $encryption ?: 'tls');
            Config::set('mail.mailers.smtp.username', $username);
            Config::set('mail.mailers.smtp.password', $password ?: '');

            if (! empty($senderName)) {
                Config::set('mail.from.name', $senderName);
            }

            if (! empty($senderEmail)) {
                Config::set('mail.from.address', $senderEmail);
            }

            // Purge the cached SMTP mailer so Laravel rebuilds it
            // with the new configuration on next send.
            Mail::purge('smtp');
        }
    }
}
