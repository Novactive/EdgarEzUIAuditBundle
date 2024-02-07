<?php

namespace Edgar\EzUIAuditBundle\Audit\Location;

use Edgar\EzUIAudit\Audit\AbstractAudit;
use Ibexa\Contracts\Core\Repository\Events\Location\CopySubtreeEvent;

class CopySubtreeAudit extends AbstractAudit
{
    public static function getSubscribedEvents()
    {
        return [
            CopySubtreeEvent::class => ['onCopySubtree', 0],
        ];
    }

    public function onCopySubtree(CopySubtreeEvent $event): void
    {
        if (!$this->auditService->isConfigured(self::class)) {
            return;
        }

        $this->infos = [
            'subtreeId' => $event->getSubtree()->id,
            'targetNewSubtreeId' => $event->getTargetParentLocation()->contentId,
            'targetParentLocationId' => $event->getTargetParentLocation()->id,
        ];

        $this->auditService->log($this);
    }
}
