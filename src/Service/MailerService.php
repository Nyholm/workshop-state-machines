<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\User;

class MailerService
{
    /**
     * Fake sending an email to a user
     */
    public function sendEmail(User $user, string $title)
    {
        echo sprintf('Sending email with title "%s" to user (ID: %s)', $title, $user->getId())."\n";
    }
}
