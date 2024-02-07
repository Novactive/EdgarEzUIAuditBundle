<?php

namespace Edgar\EzUIAuditBundle\Audit\ObjectState;

use Edgar\EzUIAudit\Audit\AbstractAudit;
use Ibexa\Contracts\Core\Repository\Events\ObjectState\SetPriorityOfObjectStateEvent;

class SetPriorityOfObjectStateAudit extends AbstractAudit
{
    public static function getSubscribedEvents()
    {
        return [
            SetPriorityOfObjectStateEvent::class => ['onSetPriorityOfObjectState', 0],
        ];
    }

    public function onSetPriorityOfObjectState(SetPriorityOfObjectStateEvent $event): void
    {
        if (!$this->auditService->isConfigured(self::class)) {
            return;
        }

        $this->infos = [
            'objectStateId' => $event->getObjectState()->id,
            'priority' => $event->getPriority(),
        ];

        $this->auditService->log($this);
    }
}
