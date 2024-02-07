<?php

namespace Edgar\EzUIAuditBundle\Audit\Location;

use Edgar\EzUIAudit\Audit\AbstractAudit;
use Ibexa\Contracts\Core\Repository\Events\Location\CreateLocationEvent;

class CreateLocationAudit extends AbstractAudit
{
    public static function getSubscribedEvents()
    {
        return [
            CreateLocationEvent::class => ['onCreateLocation', 0],
        ];
    }

    public function onCreateLocation(CreateLocationEvent $event): void
    {
        if (!$this->auditService->isConfigured(self::class)) {
            return;
        }

        $this->infos = [
            'contentId' => $event->getLocation()->contentId,
            'locationId' => $event->getLocation()->id,
            'parentLocationId' => $event->getLocation()->parentLocationId,
        ];

        $this->auditService->log($this);
    }
}
