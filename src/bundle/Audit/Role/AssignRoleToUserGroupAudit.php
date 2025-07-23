<?php

namespace Edgar\EzUIAuditBundle\Audit\Role;

use Edgar\EzUIAudit\Audit\AbstractAudit;
use Ibexa\Contracts\Core\Repository\Events\Role\AssignRoleToUserGroupEvent;

class AssignRoleToUserGroupAudit extends AbstractAudit
{
    public static function getSubscribedEvents()
    {
        return [
            AssignRoleToUserGroupEvent::class => ['onAssignRoleToUserGroup', 0],
        ];
    }

    public function onAssignRoleToUserGroup(AssignRoleToUserGroupEvent $event): void
    {
        if (!$this->auditService->isConfigured(self::class)) {
            return;
        }

        $infos = [
            'roleId' => $event->getRole()->id,
            'userGroupId' => $event->getUserGroup()->id,
        ];

        if ($event->getRoleLimitation()) {
            $infos['roleLimitation identifier'] = $event->getRoleLimitation()->getIdentifier();
        }

        $this->infos = $infos;

        $this->auditService->log($this);
    }
}
