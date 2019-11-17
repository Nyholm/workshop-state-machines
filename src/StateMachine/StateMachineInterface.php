<?php

declare(strict_types=1);

namespace App\StateMachine;

use App\Entity\User;
use App\StateMachine\State\StateInterface;

interface StateMachineInterface
{
    /**
     * @param StateInterface $state The first or current state. Ie the state we should start running now.
     * @return bool True if the job in complete and we do never have to run this state machine for this user again.
     */
    public function start(StateInterface $state): bool;

    public function getUser(): User;
    public function setState(StateInterface $state): void;
}
