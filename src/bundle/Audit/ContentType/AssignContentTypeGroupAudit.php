<?php

namespace Edgar\EzUIAuditBundle\Audit\ContentType;

use Edgar\EzUIAudit\Audit\AbstractAudit;
use Ibexa\Contracts\Core\Repository\Events\ContentType\AssignContentTypeGroupEvent;

class AssignContentTypeGroupAudit extends AbstractAudit
{
    public static function getSubscribedEvents()
    {
        return [
            AssignContentTypeGroupEvent::class => ['onAssignContentTypeGroup', 0],
        ];
    }

    public function onAssignContentTypeGroup(AssignContentTypeGroupEvent $event): void
    {
        if (!$this->auditService->isConfigured(self::class)) {
            return;
        }

        $this->infos = [
            'contentTypeGroupId' => $event->getContentTypeGroup()->id,
            'contentTypeId' => $event->getContentType()->id,
        ];

        $this->auditService->log($this);
    }
}
