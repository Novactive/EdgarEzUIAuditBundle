<?php

namespace Edgar\EzUIAuditBundle\Audit\Section;

use Edgar\EzUIAudit\Audit\AbstractAudit;
use eZ\Publish\Core\SignalSlot\Signal;

class DeleteSectionAudit extends AbstractAudit
{
    public function receive(Signal $signal)
    {
        if (!$signal instanceof Signal\SectionService\DeleteSectionSignal
            || !$this->auditService->isConfigured(self::class)
        ) {
            return;
        }

        $this->infos = [
            'sectionId' => $signal->sectionId,
        ];

        $this->auditService->log($this);
    }
}
