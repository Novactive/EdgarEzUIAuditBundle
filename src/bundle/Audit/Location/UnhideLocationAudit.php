<?php

namespace Edgar\EzUIAuditBundle\Audit\Location;

use Edgar\EzUIAudit\Audit\AbstractAudit;
use Ibexa\Contracts\Core\Repository\Events\Location\UnhideLocationEvent;

class UnhideLocationAudit extends AbstractAudit
{
    public static function getSubscribedEvents()
    {
        return [
            UnhideLocationEvent::class => ['onUnhideLocation', 0],
        ];
    }

    public function onUnhideLocation(UnhideLocationEvent $event): void
    {
        if (!$this->auditService->isConfigured(self::class)) {
            return;
        }

        $this->infos = [
            'locationId' => $event->getLocation()->id,
            'currentVersionNo' => $event->getLocation()->getContentInfo()->currentVersionNo,
            'parentLocationId' => $event->getLocation()->parentLocationId,
            'contentId' => $event->getLocation()->contentId,
        ];

        $this->auditService->log($this);
    }
}
