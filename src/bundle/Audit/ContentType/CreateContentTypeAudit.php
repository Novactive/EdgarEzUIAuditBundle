<?php

namespace Edgar\EzUIAuditBundle\Audit\ContentType;

use Edgar\EzUIAudit\Audit\AbstractAudit;
use Ibexa\Contracts\Core\Repository\Events\ContentType\CreateContentTypeEvent;

class CreateContentTypeAudit extends AbstractAudit
{
    public static function getSubscribedEvents()
    {
        return [
            CreateContentTypeEvent::class => ['onCreateContentType', 0],
        ];
    }

    public function onCreateContentType(CreateContentTypeEvent $event): void
    {
        if (!$this->auditService->isConfigured(self::class)) {
            return;
        }


        $this->infos = [
            'contentTypeId' => $event->getContentTypeDraft()->id,
        ];

        $this->auditService->log($this);
    }
}
