<?php

namespace Edgar\EzUIAuditBundle\Audit\Role;

use Edgar\EzUIAudit\Audit\AbstractAudit;
use Ibexa\Contracts\Core\Repository\Events\Role\AssignRoleToUserEvent;

class AssignRoleToUserAudit extends AbstractAudit
{
    public static function getSubscribedEvents()
    {
        return [
            AssignRoleToUserEvent::class => ['onAssignRoleToUser', 0],
        ];
    }

    public function onAssignRoleToUser(AssignRoleToUserEvent $event): void
    {
        if (!$this->auditService->isConfigured(self::class)) {
            return;
        }

        $infos = [
            'roleId' => $event->getRole()->id,
            'userId' => $event->getUser()->getUserId(),
        ];

        if ($event->getRole()) {
            $infos['roleLimitation identifier'] = $event->getRole()->identifier;
        }

        $this->infos = $infos;

        $this->auditService->log($this);
    }
}
