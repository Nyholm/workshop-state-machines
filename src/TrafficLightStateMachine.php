<?php

declare(strict_types=1);

namespace App;

class TrafficLightStateMachine
{
    /** @var string State. This variable name is used in tests. Do not rename.  */
    private $state;

    /**
     * Check if we are allowed to apply $state right now. Ie, is there an transition
     * from $this->state to $state?
     */
    public function can(string $transition): bool
    {
        switch ($this->state) {
            case 'green':
                return ($transition === 'to_yellow');
            case 'yellow':
                return ($transition === 'to_green' || $transition === 'to_red');
            case 'red':
                return ($transition === 'to_yellow');
            default:
                return false;
        }
    }

    /**
     * This will update $this->state.
     *
     * @throws \InvalidArgumentException if the $newState is invalid.
     */
    public function apply(string $transition): void
    {
        if (!$this->can($transition)) {
            throw new \InvalidArgumentException('Invalid transition');
        }

        switch ($this->state) {
            case 'green' && ($transition === 'to_yellow'):
                $this->state = 'yellow';
                break;
            case 'yellow' && ($transition === 'to_green'):
                $this->state = 'green';
                break;
            case 'yellow' && ($transition === 'to_red'):
                $this->state = 'red';
                break;
            case 'red' && ($transition === 'to_yellow'):
                $this->state = 'yellow';
                break;
        }
    }
}
