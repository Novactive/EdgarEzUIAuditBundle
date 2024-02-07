<?php

namespace Edgar\EzUIAuditBundle\Audit\ContentType;

use Edgar\EzUIAudit\Audit\AbstractAudit;
use Ibexa\Contracts\Core\Repository\Events\ContentType\DeleteContentTypeGroupEvent;

class DeleteContentTypeGroupAudit extends AbstractAudit
{
    public static function getSubscribedEvents()
    {
        return [
            DeleteContentTypeGroupEvent::class => ['onDeleteContentTypeGroup', 0],
        ];
    }

    public function onDeleteContentTypeGroup(DeleteContentTypeGroupEvent $event): void
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
