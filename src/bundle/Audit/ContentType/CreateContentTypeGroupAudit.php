<?php

namespace Edgar\EzUIAuditBundle\Audit\ContentType;

use Edgar\EzUIAudit\Audit\AbstractAudit;
use Ibexa\Contracts\Core\Repository\Events\ContentType\CreateContentTypeGroupEvent;

class CreateContentTypeGroupAudit extends AbstractAudit
{
    public static function getSubscribedEvents()
    {
        return [
            CreateContentTypeGroupEvent::class => ['onCreateContentTypeGroup', 0],
        ];
    }

    public function onCreateContentTypeGroup(CreateContentTypeGroupEvent $event): void
    {
        if (!$this->auditService->isConfigured(self::class)) {
            return;
        }

        $this->infos = [
            'groupId' => $event->getReturnValue()->id
        ];

        $this->auditService->log($this);
    }
}
