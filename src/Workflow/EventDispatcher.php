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

    public function dispatch(object $event, string $eventName = null): object
    {
        if ($event instanceof GuardEvent) {
            $this->sfEventDispatcher->dispatch($event, $eventName);

            return $event;
        } elseif (! $event instanceof Event) {
            return $event;
        }

        if ($eventName === null) {
            $eventName = get_class($event);
        }

        // transform to an App\Message\Event\Workflow\*
        foreach ($this->getMessages($event, $eventName) as $message) {
            $this->eventBus->dispatch($message);
        }

        return $event;
    }

    /**
     * Get a MessageBus message for the workflow events that we are interested in.
     */
    private function getMessages(Event $event, string $eventName): array
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
