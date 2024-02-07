<?php

namespace Edgar\EzUIAuditBundle\Audit\Role;

use Edgar\EzUIAudit\Audit\AbstractAudit;
use Ibexa\Contracts\Core\Repository\Events\Role\PublishRoleDraftEvent;

class PublishRoleDraftAudit extends AbstractAudit
{
    public static function getSubscribedEvents()
    {
        return [
            PublishRoleDraftEvent::class => ['onPublishRoleDraft', 0],
        ];
    }

    public function onPublishRoleDraft(PublishRoleDraftEvent $event): void
    {
        if (!$this->auditService->isConfigured(self::class)) {
            return;
        }

        $this->infos = [
            'roleId' => $event->getRoleDraft()->id,
        ];

        $this->auditService->log($this);
    }
}
