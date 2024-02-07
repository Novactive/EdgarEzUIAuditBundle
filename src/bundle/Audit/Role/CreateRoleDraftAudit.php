<?php

namespace Edgar\EzUIAuditBundle\Audit\Role;

use Edgar\EzUIAudit\Audit\AbstractAudit;
use Ibexa\Contracts\Core\Repository\Events\Role\CreateRoleDraftEvent;

class CreateRoleDraftAudit extends AbstractAudit
{
    public static function getSubscribedEvents()
    {
        return [
            CreateRoleDraftEvent::class => ['onCreateRoleDraft', 0],
        ];
    }

    public function onCreateRoleDraft(CreateRoleDraftEvent $event): void
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
