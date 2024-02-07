<?php

namespace Edgar\EzUIAuditBundle\Audit\Location;

use Edgar\EzUIAudit\Audit\AbstractAudit;
use Ibexa\Contracts\Core\Repository\Events\Location\SwapLocationEvent;

class SwapLocationAudit extends AbstractAudit
{
    public static function getSubscribedEvents()
    {
        return [
            SwapLocationEvent::class => ['onSwapLocation', 0],
        ];
    }

    public function onSwapLocation(SwapLocationEvent $event): void
    {
        if (!$this->auditService->isConfigured(self::class)) {
            return;
        }

        $this->infos = [
            'content1Id' => $event->getLocation1()->contentId,
            'content2Id' => $event->getLocation2()->contentId,
            'location1Id' => $event->getLocation1()->id,
            'location2Id' => $event->getLocation2()->id,
            'parentLocation1Id' => $event->getLocation1()->parentLocationId,
            'parentLocation2Id' => $event->getLocation2()->parentLocationId,
        ];

        $this->auditService->log($this);
    }
}
