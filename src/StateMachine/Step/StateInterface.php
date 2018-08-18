<?php

declare(strict_types=1);

namespace App\StateMachine\Step;

use App\Service\MailerService;
use App\StateMachine\StateMachineInterface;

interface StateInterface
{
    const CONTINUE = 0;
    const STOP = 1;

    /**
     * @return int To communicate back to the state machine if we should self::STOP running
     *             or if we should self::CONTINUE with the next state.
     */
    public function send(StateMachineInterface $stateMachine, MailerService $mailer): int;
}