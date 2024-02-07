<?php

namespace Edgar\EzUIAuditBundle\Audit\Section;

use Edgar\EzUIAudit\Audit\AbstractAudit;
use Ibexa\Contracts\Core\Repository\Events\Section\DeleteSectionEvent;

class DeleteSectionAudit extends AbstractAudit
{
    public static function getSubscribedEvents()
    {
        return [
            DeleteSectionEvent::class => ['onDeleteSectionEvent', 0],
        ];
    }

    public function onDeleteSectionEvent(DeleteSectionEvent $event): void
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
