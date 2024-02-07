<?php

namespace Edgar\EzUIAuditBundle\Audit\Section;

use Edgar\EzUIAudit\Audit\AbstractAudit;
use Ibexa\Contracts\Core\Repository\Events\Section\UpdateSectionEvent;

class UpdateSectionAudit extends AbstractAudit
{
    public static function getSubscribedEvents()
    {
        return [
            UpdateSectionEvent::class => ['onUpdateSection', 0],
        ];
    }

    public function onUpdateSection(UpdateSectionEvent $event): void
    {
        if (!$this->auditService->isConfigured(self::class)) {
            return;
        }

        $this->infos = [
            'sectionId' => $event->getSection()->id
        ];

        $this->auditService->log($this);
    }
}
