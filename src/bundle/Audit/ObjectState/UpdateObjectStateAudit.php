<?php

namespace Edgar\EzUIAuditBundle\Audit\ObjectState;

use Edgar\EzUIAudit\Audit\AbstractAudit;
use Ibexa\Contracts\Core\Repository\Events\ObjectState\UpdateObjectStateEvent;

class UpdateObjectStateAudit extends AbstractAudit
{
    public static function getSubscribedEvents()
    {
        return [
            UpdateObjectStateEvent::class => ['onUpdateObjectState', 0],
        ];
    }

    public function onUpdateObjectState(UpdateObjectStateEvent $event): void
    {
        if (!$this->auditService->isConfigured(self::class)) {
            return;
        }

        $this->infos = [
            'objectStateId' => $event->getObjectState()->id,
        ];

        $this->auditService->log($this);
    }
    public function receive(Event $Event)
    {
        if (!$Event instanceof Event\ObjectStateService\UpdateObjectStateEvent
            || !$this->auditService->isConfigured(self::class)
        ) {
            return;
        }

        $this->infos = [
            'objectStateId' => $Event->objectStateId,
        ];

        $this->auditService->log($this);
    }
}
