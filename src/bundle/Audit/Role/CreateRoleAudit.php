<?php

namespace Edgar\EzUIAuditBundle\Audit\Role;

use Edgar\EzUIAudit\Audit\AbstractAudit;
use Ibexa\Contracts\Core\Repository\Events\Role\CreateRoleEvent;

class CreateRoleAudit extends AbstractAudit
{
    public static function getSubscribedEvents()
    {
        return [
            CreateRoleEvent::class => ['onCreateRoleEvent', 0],
        ];
    }

    public function onCreateRoleEvent(CreateRoleEvent $event): void
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
