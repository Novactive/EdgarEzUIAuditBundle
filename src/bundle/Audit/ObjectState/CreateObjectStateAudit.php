<?php

namespace Edgar\EzUIAuditBundle\Audit\ObjectState;

use Edgar\EzUIAudit\Audit\AbstractAudit;
use Ibexa\Contracts\Core\Repository\Events\ObjectState\CreateObjectStateEvent;

class CreateObjectStateAudit extends AbstractAudit
{
    public static function getSubscribedEvents()
    {
        return [
            CreateObjectStateEvent::class => ['onCreateObjectState', 0],
        ];
    }

    public function onCreateObjectState(CreateObjectStateEvent $event): void
    {
        if (!$this->auditService->isConfigured(self::class)) {
            return;
        }

        $this->infos = [
            'objectStateGroupId' => $event->getObjectStateGroup()->id,
            'objectStateId' => $event->getObjectState()->id,
        ];

        $this->auditService->log($this);
    }
}
