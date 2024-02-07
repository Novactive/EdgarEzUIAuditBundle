<?php

namespace Edgar\EzUIAuditBundle\Audit\User;

use Edgar\EzUIAudit\Audit\AbstractAudit;
use Ibexa\Contracts\Core\Repository\Events\User\DeleteUserGroupEvent;

class DeleteUserGroupAudit extends AbstractAudit
{
    public static function getSubscribedEvents()
    {
        return [
            DeleteUserGroupEvent::class => ['onDeleteUserGroup', 0],
        ];
    }

    public function onDeleteUserGroup(DeleteUserGroupEvent $event): void
    {
        if (!$this->auditService->isConfigured(self::class)) {
            return;
        }

        $this->infos = [
            'userGroupId' => $event->getUserGroup()->id,
            'affectedLocationIds' => $event->getLocations(),
        ];

        $this->auditService->log($this);
    }
}
