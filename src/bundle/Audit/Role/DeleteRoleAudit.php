<?php

namespace Edgar\EzUIAuditBundle\Audit\Role;

use Edgar\EzUIAudit\Audit\AbstractAudit;
use Ibexa\Contracts\Core\Repository\Events\Role\DeleteRoleEvent;

class DeleteRoleAudit extends AbstractAudit
{
    public static function getSubscribedEvents()
    {
        return [
            DeleteRoleEvent::class => ['onDeleteRole', 0],
        ];
    }

    public function onDeleteRole(DeleteRoleEvent $event): void
    {
        if (!$this->auditService->isConfigured(self::class)) {
            return;
        }

        $this->infos = [
            'roleId' => $event->getRole()->id,
        ];

        $this->auditService->log($this);
    }
}
