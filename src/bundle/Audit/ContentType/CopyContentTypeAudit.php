<?php

namespace Edgar\EzUIAuditBundle\Audit\ContentType;

use Edgar\EzUIAudit\Audit\AbstractAudit;
use Ibexa\Contracts\Core\Repository\Events\ContentType\CopyContentTypeEvent;

class CopyContentTypeAudit extends AbstractAudit
{
    public static function getSubscribedEvents()
    {
        return [
            CopyContentTypeEvent::class => ['onCopyContentType', 0],
        ];
    }

    public function onCopyContentType(CopyContentTypeEvent $event): void
    {
        if (!$this->auditService->isConfigured(self::class)) {
            return;
        }

        $this->infos = [
            'contentTypeId' => $event->getContentType()->id,
            'userId' => $event->getContentTypeCopy()->creatorId,
        ];

        $this->auditService->log($this);
    }
}
