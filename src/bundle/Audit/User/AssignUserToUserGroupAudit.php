<?php

namespace Edgar\EzUIAuditBundle\Audit\User;

use Edgar\EzUIAudit\Audit\AbstractAudit;
use Ibexa\Contracts\Core\Repository\Events\User\AssignUserToUserGroupEvent;

class AssignUserToUserGroupAudit extends AbstractAudit
{
    public static function getSubscribedEvents()
    {
        return [
            AssignUserToUserGroupEvent::class => ['onAssignUserToUserGroup', 0],
        ];
    }

    public function onAssignUserToUserGroup(AssignUserToUserGroupEvent $event): void
    {
        if (!$this->auditService->isConfigured(self::class)) {
            return;
        }

        $this->infos = [
            'userGroupId' => $event->getUserGroup()->id,
            'userId' => $event->getUser()->getUserId(),
        ];

        $this->auditService->log($this);
    }
}
