<?php

namespace Edgar\EzUIAuditBundle\Audit\Content;

use Ibexa\Contracts\Core\Repository\Events\Content\CopyContentEvent;
use Edgar\EzUIAudit\Audit\AbstractAudit;

class CopyContentAudit extends AbstractAudit
{
    public static function getSubscribedEvents()
    {
        return [
            CopyContentEvent::class => ['onCopyContent', 0],
        ];
    }

    public function onCopyContent(CopyContentEvent $event): void
    {
        if (!$this->auditService->isConfigured(self::class)) {
            return;
        }

        $this->infos = [
            'srcContentId' => $event->getContent()->id,
            'srcVersionNo' => $event->getContent()->getVersionInfo()->versionNo,
            'dstParentLocationId' => $event->getDestinationLocationCreateStruct()->parentLocationId,
        ];
        $this->auditService->log($this);
    }
}