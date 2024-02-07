<?php

namespace Edgar\EzUIAuditBundle\Audit\Location;

use Edgar\EzUIAudit\Audit\AbstractAudit;
use Ibexa\Contracts\Core\Repository\Events\Location\HideLocationEvent;

class HideLocationAudit extends AbstractAudit
{
    public static function getSubscribedEvents()
    {
        return [
            HideLocationEvent::class => ['onHideLocation', 0],
        ];
    }

    public function onHideLocation(HideLocationEvent $event): void
    {
        if (!$this->auditService->isConfigured(self::class)) {
            return;
        }

        $this->infos = [
            'contentId' => $event->getLocation()->contentId,
            'locationId' => $event->getLocation()->id,
            'parentLocationId' => $event->getLocation()->parentLocationId,
            'currentVersionNo' => $event->getLocation()->getContentInfo()->currentVersionNo,
        ];
        dd($this);

        $this->auditService->log($this);
    }
}
