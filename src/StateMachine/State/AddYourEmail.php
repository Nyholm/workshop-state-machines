<?php

declare(strict_types=1);

namespace App\StateMachine\State;

use App\Service\MailerService;
use App\StateMachine\StateMachineInterface;
use App\WorldClock;

class AddYourEmail implements StateInterface
{
    public function send(StateMachineInterface $stateMachine, MailerService $mailer): int
    {
        $user = $stateMachine->getUser();
        if (!empty($user->getEmail())) {
            $stateMachine->setState(new AddYourTwitter());

            return self::CONTINUE;
        }

        // Make sure we do not send emails too often
        if ($user->getLastSentAt() > WorldClock::getDateTimeRelativeFakeTime('-24hours')) {
            return self::STOP;
        }

        $mailer->sendEmail($user, 'AddYourEmail');
        $stateMachine->setState(new AddYourTwitter());

        return self::CONTINUE;
    }
}
