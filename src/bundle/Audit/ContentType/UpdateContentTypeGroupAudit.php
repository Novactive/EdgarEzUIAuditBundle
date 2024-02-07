<?php

namespace Edgar\EzUIAuditBundle\Audit\ContentType;

use Edgar\EzUIAudit\Audit\AbstractAudit;
use Ibexa\Contracts\Core\Repository\Events\ContentType\UpdateContentTypeGroupEvent;

class UpdateContentTypeGroupAudit extends AbstractAudit
{
    public static function getSubscribedEvents()
    {
        return [
            UpdateContentTypeGroupEvent::class => ['onUpdateContentTypeGroup', 0],
        ];
    }

    public function onUpdateContentTypeGroup(UpdateContentTypeGroupEvent $event): void
    {
        if (!$this->auditService->isConfigured(self::class)) {
            return;
        }

        $this->infos = [
            'contentTypeGroupId' => $event->getContentTypeGroup()->id,
        ];

        $this->auditService->log($this);
    }
}
