<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Workflow\Event\Event;

class TrafficLightLogger implements EventSubscriberInterface
{

    private $logger;

    /**
     *
     * @param $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }


    public static function getSubscribedEvents()
    {
        return [
            'workflow.traffic_light.transition'=>'onTransition'
        ];
    }


    public function onTransition(Event $event)
    {
        $this->logger->error(sprintf('Moved from "%s" to "%s".', implode(', ',$event->getTransition()->getFroms()), implode(', ',$event->getTransition()->getTos())));
    }
}
