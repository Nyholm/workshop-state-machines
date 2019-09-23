<?php

declare(strict_types=1);

namespace App\Workflow;

use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Workflow\Event\Event;
use Symfony\Component\Workflow\Event\GuardEvent;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

final class EventDispatcher implements EventDispatcherInterface
{
    private $eventBus;
    private $sfEventDispatcher;

    public function __construct(MessageBusInterface $eventBus, EventDispatcherInterface $sfEventDispatcher)
    {
        $this->eventBus = $eventBus;
        $this->sfEventDispatcher = $sfEventDispatcher;
    }

    public function dispatch($event/*, string $eventName = null*/)
    {
        $eventName = 1 < \func_num_args() ? \func_get_arg(1) : null;

        if (\is_object($event)) {
            $eventName = $eventName ?? \get_class($event);
        } elseif (\is_string($event) && $eventName instanceof Event) {
            $swap = $event;
            $event = $eventName;
            $eventName = $swap;
        } else {
            throw new \TypeError(\sprintf('Argument 1 passed to "%s::dispatch()" must be an object, %s given.', EventDispatcherInterface::class, \is_object($event) ? \get_class($event) : \gettype($event)));
        }

        if ($event instanceof GuardEvent) {
            $this->sfEventDispatcher->dispatch($event, $eventName);

            return;
        }

        // transform to an App\Message\Event\Workflow\*
        foreach ($this->getMessages($eventName, $event) as $message) {
            $this->eventBus->dispatch($message);
        }
    }

    /**
     * Get a MessageBus message for the workflow events that we are interested in.
     */
    private function getMessages(string $eventName, Event $event): array
    {
        switch ($eventName) {
            case 'workflow.traffic_light.completed.to_red':
                return [ToRed::create($event->getSubject()->getId())];

            case 'workflow.traffic_light.entered.yellow':
                return [EnteredYellow::create($event->getSubject()->getId())];

            case 'workflow.traffic_light.enter.green':
                return [EnterGreen::create($event->getSubject()->getId())];

            default:
                return [];
        }
    }
}
