<?php

namespace Edgar\EzUIAuditBundle\Audit\ContentType;

use Edgar\EzUIAudit\Audit\AbstractAudit;
use Ibexa\Contracts\Core\Repository\Events\ContentType\UpdateContentTypeDraftEvent;

class UpdateContentTypeDraftAudit extends AbstractAudit
{
    public static function getSubscribedEvents()
    {
        return [
            UpdateContentTypeDraftEvent::class => ['onUpdateContentTypeDraft', 0],
        ];
    }

    public function onUpdateContentTypeDraft(UpdateContentTypeDraftEvent $event): void
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
