<?php

declare(strict_types=1);

namespace App\StateMachine\Step;

use App\Service\MailerService;
use App\StateMachine\StateMachineInterface;

class AddYourEmail implements StateInterface
{
    public function send(StateMachineInterface $stateMachine, MailerService $mailer): int
    {
        $user = $stateMachine->getUser();
        if (!empty($user->getEmail())) {
            $stateMachine->setState(new FinalState());

            return self::CONTINUE;
        }

        $mailer->sendEmail($user, 'AddYourEmail');
        $stateMachine->setState(new FinalState());

        return self::CONTINUE;
    }

}