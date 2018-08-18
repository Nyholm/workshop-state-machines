<?php

declare(strict_types=1);

namespace App;

class StateMachine
{
    /**
     * @var array with data that holds data about from/to state and transitions.
     *            array('from_state' => ['transition' => 'to_state'])
     */
    private $transitions;

    public function __construct(array $transitions)
    {
        $this->transitions = $transitions;
    }

    /**
     * Check if we are allowed to apply $state right now. Ie, is there an transition
     * from $this->state to $state?
     */
    public function can(StateAwareInterface $object, string $transition): bool
    {
        $state = $object->getState();

        return isset($this->transitions[$state][$transition]);
    }

    /**
     * @throws \InvalidArgumentException if the $newState is invalid.
     */
    public function apply(StateAwareInterface $object, $transition): void
    {
        if (!$this->can($object, $transition)) {
            throw new \InvalidArgumentException(sprintf('Invalid transition "%s" when in state "%s".', $transition, $object->getState()));
        }

        $state = $object->getState();
        $newState = $this->transitions[$state][$transition];
        $object->setState($newState);
    }

    public function getCurrentState(StateAwareInterface $object): string
    {
        // TODO write me

        return '';
    }

    public function getValidTransitions(StateAwareInterface $object): array
    {
        // TODO write me

        return [];
    }
}
