<?php

declare(strict_types=1);

namespace App\StateMachine\State;

use App\Service\MailerService;
use App\StateMachine\StateMachineInterface;

class AddYourName implements StateInterface
{
    public function send(StateMachineInterface $stateMachine, MailerService $mailer): int
    {
        $user = $stateMachine->getUser();
        if (!empty($user->getName())) {
            // TODO Update the state
        } else {
            // TODO send the email
        }

        return self::CONTINUE;
    }
}