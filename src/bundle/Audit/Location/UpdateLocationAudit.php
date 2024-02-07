<?php

namespace Edgar\EzUIAuditBundle\Audit\Location;

use Edgar\EzUIAudit\Audit\AbstractAudit;
use Ibexa\Contracts\Core\Repository\Events\Location\UpdateLocationEvent;

class UpdateLocationAudit extends AbstractAudit
{
    public static function getSubscribedEvents()
    {
        return [
            UpdateLocationEvent::class => ['onUpdateLocation', 0],
        ];
    }

    public function onUpdateLocation(UpdateLocationEvent $event): void
    {
        if (!$this->auditService->isConfigured(self::class)) {
            return;
        }

        $this->infos = [
            'contentId' => $event->getLocation()->contentId,
            'parentLocationId' => $event->getLocation()->parentLocationId,
            'locationId' => $event->getLocation()->id,
        ];

        $this->auditService->log($this);
    }
}
