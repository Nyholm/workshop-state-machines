<?php

declare(strict_types=1);

namespace App\StateMachine\Step;

use App\Service\MailerService;
use App\StateMachine\StateMachineInterface;
use App\WorldClock;

class AddYourTwitter implements StateInterface
{
    public function send(StateMachineInterface $stateMachine, MailerService $mailer): int
    {
        $user = $stateMachine->getUser();
        if (!empty($user->getTwitter())) {
            $stateMachine->setState(new FinalState());

            return self::CONTINUE;
        }

        // Make sure we do not send emails too often
        if ($user->getLastSentAt() > WorldClock::getDateTimeRelativeFakeTime('-24hours')) {
            return self::STOP;
        }

        $mailer->sendEmail($user, 'AddYourTwitter');
        $stateMachine->setState(new FinalState());

        return self::CONTINUE;
    }
}
