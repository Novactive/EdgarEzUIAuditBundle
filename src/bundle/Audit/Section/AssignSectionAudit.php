<?php

namespace Edgar\EzUIAuditBundle\Audit\Section;

use Edgar\EzUIAudit\Audit\AbstractAudit;
use Ibexa\Contracts\Core\Repository\Events\Section\AssignSectionEvent;

class AssignSectionAudit extends AbstractAudit
{
    public static function getSubscribedEvents()
    {
        return [
            AssignSectionEvent::class => ['onAssignSection', 0],
        ];
    }

    public function onAssignSection(AssignSectionEvent $event): void
    {
        if (!$this->auditService->isConfigured(self::class)) {
            return;
        }

        $this->infos = [
            'contentId' => $event->getContentInfo()->id,
            'sectionId' => $event->getSection()->id,
        ];

        $this->auditService->log($this);
    }
}
