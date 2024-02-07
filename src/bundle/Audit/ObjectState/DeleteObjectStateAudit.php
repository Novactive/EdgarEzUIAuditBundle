<?php

namespace Edgar\EzUIAuditBundle\Audit\ObjectState;

use Edgar\EzUIAudit\Audit\AbstractAudit;
use Ibexa\Contracts\Core\Repository\Events\ObjectState\DeleteObjectStateEvent;

class DeleteObjectStateAudit extends AbstractAudit
{
    public static function getSubscribedEvents()
    {
        return [
            DeleteObjectStateEvent::class => ['onDeleteObjectStateEvent', 0],
        ];
    }

    public function onDeleteObjectStateEvent(DeleteObjectStateEvent $event): void
    {
        if (!$this->auditService->isConfigured(self::class)) {
            return;
        }

        $this->infos = [
            'objectStateId' => $event->getObjectState()->id,
        ];

        $this->auditService->log($this);
    }
}
