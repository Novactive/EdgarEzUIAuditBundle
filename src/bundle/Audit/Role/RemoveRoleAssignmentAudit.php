<?php

namespace Edgar\EzUIAuditBundle\Audit\Role;

use Edgar\EzUIAudit\Audit\AbstractAudit;
use Ibexa\Contracts\Core\Repository\Events\Role\RemoveRoleAssignmentEvent;

class RemoveRoleAssignmentAudit extends AbstractAudit
{
    public static function getSubscribedEvents()
    {
        return [
            RemoveRoleAssignmentEvent::class => ['onRemoveRoleAssignment', 0],
        ];
    }

    public function onRemoveRoleAssignment(RemoveRoleAssignmentEvent $event): void
    {
        if (!$this->auditService->isConfigured(self::class)) {
            return;
        }

        $this->infos = [
            'roleAssignmentId' => $event->getRoleAssignment()->getRole()->id,
        ];

        $this->auditService->log($this);
    }
}
