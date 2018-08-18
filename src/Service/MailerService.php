<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\User;
use App\WorldClock;

class MailerService
{
    /**
     * Fake sending an email to a user
     */
    public function sendEmail(User $user, string $title)
    {
        $user->setLastSentAt(WorldClock::getDateTimeRelativeFakeTime());
        echo sprintf('Sending email to user (ID: %s) with title "%s"', $user->getId(), $title)."\n";
    }
}
