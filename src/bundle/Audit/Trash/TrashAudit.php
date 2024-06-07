<?php

namespace Edgar\EzUIAuditBundle\Audit\Trash;

use Edgar\EzUIAudit\Audit\AbstractAudit;
use Ibexa\Contracts\Core\Repository\Events\Trash\TrashEvent;

class TrashAudit extends AbstractAudit
{
    public static function getSubscribedEvents()
    {
        return [
            TrashEvent::class => ['onTrash', 0],
        ];
    }

    public function onTrash(TrashEvent $event): void
    {
        if (!$this->auditService->isConfigured(self::class)) {
            return;
        }

        $this->infos = [
            'contentId' => $event->getTrashItem()->contentId,
            'locationId' => $event->getLocation()->id,
            'parentLocationId' => $event->getLocation()->parentLocationId
        ];

        $this->auditService->log($this);
    }
}
