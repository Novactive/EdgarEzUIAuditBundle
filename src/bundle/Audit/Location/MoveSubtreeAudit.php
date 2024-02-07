<?php

namespace Edgar\EzUIAuditBundle\Audit\Location;

use Edgar\EzUIAudit\Audit\AbstractAudit;
use Ibexa\Contracts\Core\Repository\Events\Location\MoveSubtreeEvent;

class MoveSubtreeAudit extends AbstractAudit
{
    public static function getSubscribedEvents()
    {
        return [
            MoveSubtreeEvent::class => ['onMoveSubtree', 0],
        ];
    }

    public function onMoveSubtree(MoveSubtreeEvent $event): void
    {
        if (!$this->auditService->isConfigured(self::class)) {
            return;
        }

        $this->infos = [
            'locationId' => $event->getLocation()->id,
            'newParentLocationId' => $event->getNewParentLocation()->id,
            'oldParentLocationId' => $event->getLocation()->parentLocationId,
        ];

        $this->auditService->log($this);
    }
}
