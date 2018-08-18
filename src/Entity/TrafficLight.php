<?php

declare(strict_types=1);

namespace App\Entity;

use App\StateAwareInterface;

class TrafficLight implements StateAwareInterface
{
    private $state;

    public function __construct(string $state = 'red')
    {
        $this->state = $state;
    }

    public function getState(): string
    {
        return $this->state;
    }

    public function setState(string $state): void
    {
        $this->state = $state;
    }
}