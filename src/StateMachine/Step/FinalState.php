<?php

declare(strict_types=1);

namespace App\StateMachine\Step;

use App\Service\MailerService;
use App\StateMachine\StateMachineInterface;

class FinalState implements StateInterface
{
    public function send(StateMachineInterface $stateMachine, MailerService $mailer): int
    {
        return self::STOP;
    }
}
