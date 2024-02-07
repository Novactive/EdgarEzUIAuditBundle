<?php

namespace Edgar\EzUIAuditBundle\Audit\ContentType;

use Edgar\EzUIAudit\Audit\AbstractAudit;
use Ibexa\Contracts\Core\Repository\Events\ContentType\PublishContentTypeDraftEvent;

class PublishContentTypeDraftAudit extends AbstractAudit
{
    public static function getSubscribedEvents()
    {
        return [
            PublishContentTypeDraftEvent::class => ['onPublishContentTypeDraft', 0],
        ];
    }

    public function onPublishContentTypeDraft(PublishContentTypeDraftEvent $event): void
    {
        if (!$this->auditService->isConfigured(self::class)) {
            return;
        }

        $this->infos = [
            'contentTypeDraftId' => $event->getContentTypeDraft()->id,
        ];

        $this->auditService->log($this);
    }
}
