<?php

declare(strict_types=1);

namespace App\StateMachine\Step;

use App\Service\MailerService;
use App\StateMachine\StateMachineInterface;
use App\WorldClock;

class AddYourName implements StateInterface
{
    public function send(StateMachineInterface $stateMachine, MailerService $mailer): int
    {
        $user = $stateMachine->getUser();
        if (!empty($user->getName())) {
            $stateMachine->setState(new AddYourEmail());

            return self::CONTINUE;
        }

        // Make sure we do not send emails too often
        if ($user->getLastSentAt() > WorldClock::getDateTimeRelativeFakeTime('-24hours')) {
            return self::STOP;
        }

        $mailer->sendEmail($user, 'AddYourName');
        $stateMachine->setState(new AddYourEmail());

        return self::CONTINUE;
    }
}
