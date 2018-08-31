<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Workflow\Event\Event;
use Symfony\Component\Workflow\Event\GuardEvent;

class TrafficLightGuard implements EventSubscriberInterface
{
    private $authorizationChecker;

    public function __construct(AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->authorizationChecker = $authorizationChecker;
    }


    public static function getSubscribedEvents()
    {
        return [
            'workflow.traffic_light.guard.to_red'=>'onTransition'
        ];
    }


    public function onTransition(GuardEvent $event)
    {
        if (!$this->authorizationChecker->isGranted('ROLE_ADMIN')) {
            $event->setBlocked(true);
        }
    }
}
