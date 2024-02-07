<?php

namespace Edgar\EzUIAuditBundle\Audit\Trash;

use Edgar\EzUIAudit\Audit\AbstractAudit;
use Ibexa\Contracts\Core\Repository\Events\Trash\DeleteTrashItemEvent;

class DeleteTrashItemAudit extends AbstractAudit
{
    public static function getSubscribedEvents()
    {
        return [
            DeleteTrashItemEvent::class => ['onDeleteTrashItem', 0],
        ];
    }

    public function onDeleteTrashItem(DeleteTrashItemEvent $event): void
    {
        if (!$this->auditService->isConfigured(self::class)) {
            return;
        }

        $this->infos = [
            'trashItemId' => $event->getTrashItem()->id,
        ];

        $this->auditService->log($this);
    }
}
