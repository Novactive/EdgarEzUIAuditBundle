<?php

namespace Edgar\EzUIAuditBundle\Audit\ObjectState;

use Edgar\EzUIAudit\Audit\AbstractAudit;
use Ibexa\Contracts\Core\Repository\Events\ObjectState\DeleteObjectStateGroupEvent;

class DeleteObjectStateGroupAudit extends AbstractAudit
{
    public static function getSubscribedEvents()
    {
        return [
            DeleteObjectStateGroupEvent::class => ['onDeleteObjectStateGroup', 0],
        ];
    }

    public function onDeleteObjectStateGroup(DeleteObjectStateGroupEvent $event): void
    {
        if (!$this->auditService->isConfigured(self::class)) {
            return;
        }

        $this->infos = [
            'objectStateGroupId' => $Event->objectStateGroupId,
        ];

        $this->auditService->log($this);
    }
}
