<?php

namespace Edgar\EzUIAuditBundle\Audit\Trash;

use Edgar\EzUIAudit\Audit\AbstractAudit;
use Ibexa\Contracts\Core\Repository\Events\Trash\EmptyTrashEvent;

class EmptyTrashAudit extends AbstractAudit
{
    public static function getSubscribedEvents()
    {
        return [
            EmptyTrashEvent::class => ['onEmptyTrash', 0],
        ];
    }

    public function onEmptyTrash(EmptyTrashEvent $event): void
    {
        if (!$this->auditService->isConfigured(self::class)) {
            return;
        }

        $this->infos = [
        ];

        $this->auditService->log($this);
    }
}
