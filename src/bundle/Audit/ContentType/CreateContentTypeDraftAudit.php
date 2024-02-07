<?php

namespace Edgar\EzUIAuditBundle\Audit\ContentType;

use Edgar\EzUIAudit\Audit\AbstractAudit;
use Ibexa\Contracts\Core\Repository\Events\ContentType\CreateContentTypeDraftEvent;

class CreateContentTypeDraftAudit extends AbstractAudit
{
    public static function getSubscribedEvents()
    {
        return [
            CreateContentTypeDraftEvent::class => ['onCreateContentTypeDraft', 0],
        ];
    }

    public function onCreateContentTypeDraft(CreateContentTypeDraftEvent $event): void
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
