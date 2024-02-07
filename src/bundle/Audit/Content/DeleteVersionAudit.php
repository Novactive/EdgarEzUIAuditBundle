<?php

namespace Edgar\EzUIAuditBundle\Audit\Content;

use Edgar\EzUIAudit\Audit\AbstractAudit;
use Ibexa\Contracts\Core\Repository\Events\Content\DeleteVersionEvent;

class DeleteVersionAudit extends AbstractAudit
{
    public static function getSubscribedEvents()
    {
        return [
            DeleteVersionEvent::class => ['onDeleteVersion', 0],
        ];
    }

    public function onDeleteVersion(DeleteVersionEvent $event): void
    {
        if (!$this->auditService->isConfigured(self::class)) {
            return;
        }
        $this->infos = [
            'versionNo' => $event->getVersionInfo()->versionNo,
            'contentId' => $event->getVersionInfo()->contentInfo->id,
        ];
        $this->auditService->log($this);
    }
}
