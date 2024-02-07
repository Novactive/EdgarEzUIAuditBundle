<?php

namespace Edgar\EzUIAuditBundle\Audit\Content;

use Edgar\EzUIAudit\Audit\AbstractAudit;
use Ibexa\Contracts\Core\Repository\Events\Content\UpdateContentMetadataEvent;

class UpdateContentMetadataAudit extends AbstractAudit
{
    public static function getSubscribedEvents()
    {
        return [
            UpdateContentMetadataEvent::class => ['onUpdateContentMetadata', 0],
        ];
    }

    public function onUpdateContentMetadata(UpdateContentMetadataEvent $event): void
    {
        if (!$this->auditService->isConfigured(self::class)) {
            return;
        }

        $this->infos = [
            'contentId' => $event->getContent()->id,
        ];
        $this->auditService->log($this);
    }
}
