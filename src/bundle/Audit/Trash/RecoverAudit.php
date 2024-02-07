<?php

namespace Edgar\EzUIAuditBundle\Audit\Trash;

use Edgar\EzUIAudit\Audit\AbstractAudit;
use Ibexa\Contracts\Core\Repository\Events\Trash\RecoverEvent;

class RecoverAudit extends AbstractAudit
{
    public static function getSubscribedEvents()
    {
        return [
            RecoverEvent::class => ['onRecoverEvent', 0],
        ];
    }

    public function onRecoverEvent(RecoverEvent $event): void
    {
        if (!$this->auditService->isConfigured(self::class)) {
            return;
        }

        $this->infos = [
            'trashItemId' => $event->getTrashItem()->id,
            'contentId' => $event->getTrashItem()->contentId,
            'newParentLocationId' => $event->getNewParentLocation()->id,
            'newLocationId' => $event->getLocation()->id,
        ];

        $this->auditService->log($this);
    }
}
