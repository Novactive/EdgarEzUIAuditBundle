<?php

namespace Edgar\EzUIAuditBundle\Audit\User;

use Edgar\EzUIAudit\Audit\AbstractAudit;
use Ibexa\Contracts\Core\Repository\Events\User\UnAssignUserFromUserGroupEvent;

class UnAssignUserFromUserGroupAudit extends AbstractAudit
{
    public static function getSubscribedEvents()
    {
        return [
            UnAssignUserFromUserGroupEvent::class => ['onUnAssignUserFromUserGroup', 0],
        ];
    }

    public function onUnAssignUserFromUserGroup(UnAssignUserFromUserGroupEvent $event): void
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
