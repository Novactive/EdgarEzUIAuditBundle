<?php

namespace Edgar\EzUIAuditBundle\Audit\Section;

use Edgar\EzUIAudit\Audit\AbstractAudit;
use Ibexa\Contracts\Core\Repository\Events\Section\CreateSectionEvent;

class CreateSectionAudit extends AbstractAudit
{
    public static function getSubscribedEvents()
    {
        return [
            CreateSectionEvent::class => ['onCreateSection', 0],
        ];
    }

    public function onCreateSection(CreateSectionEvent $event): void
    {
        if (!$this->auditService->isConfigured(self::class)) {
            return;
        }

        $this->infos = [
            'sectionId' => $event->getSection()->id,
        ];

        $this->auditService->log($this);
    }
}
