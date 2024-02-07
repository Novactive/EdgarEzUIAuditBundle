<?php

namespace Edgar\EzUIAuditBundle\Audit\ContentType;

use Edgar\EzUIAudit\Audit\AbstractAudit;
use Ibexa\Contracts\Core\Repository\Events\ContentType\DeleteContentTypeEvent;

class DeleteContentTypeAudit extends AbstractAudit
{
    public static function getSubscribedEvents()
    {
        return [
            DeleteContentTypeEvent::class => ['onDeleteContentType', 0],
        ];
    }

    public function onDeleteContentType(DeleteContentTypeEvent $event): void
    {
        if (!$this->auditService->isConfigured(self::class)) {
            return;
        }

        $this->infos = [
            'contentTypeId' => $event->getContentType()->id,
        ];

        $this->auditService->log($this);
    }
}
