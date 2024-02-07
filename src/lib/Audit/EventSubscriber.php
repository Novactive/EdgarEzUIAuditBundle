<?php

namespace Edgar\EzUIAudit\Audit;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
/**
 * A Slot can be assigned to receive a certain Signal.
 */
abstract class EventSubscriber implements EventSubscriberInterface
{
    /**
     * @return mixed
     */
    abstract  static function getSubscribedEvents();
}
