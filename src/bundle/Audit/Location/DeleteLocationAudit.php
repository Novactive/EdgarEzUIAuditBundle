<?php

namespace Edgar\EzUIAuditBundle\Audit\Location;

use Edgar\EzUIAudit\Audit\AbstractAudit;
use eZ\Publish\Core\SignalSlot\Signal;

class DeleteLocationAudit extends AbstractAudit
{
    public function receive(Signal $signal)
    {
        if (!$signal instanceof Signal\LocationService\DeleteLocationSignal
            || !$this->auditService->isConfigured(self::class)
        ) {
            return;
        }

        $this->infos = [
            'parentLocationId' => $signal->parentLocationId,
            'locationId' => $signal->locationId,
            'contentId' => $signal->contentId,
        ];

        $this->auditService->log($this);
    }
}
