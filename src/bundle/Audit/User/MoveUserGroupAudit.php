<?php

namespace Edgar\EzUIAuditBundle\Audit\User;

use Edgar\EzUIAudit\Audit\AbstractAudit;
use Ibexa\Contracts\Core\Repository\Events\User\MoveUserGroupEvent;

class MoveUserGroupAudit extends AbstractAudit
{
    public static function getSubscribedEvents()
    {
        return [
            MoveUserGroupEvent::class => ['onMoveUserGroup', 0],
        ];
    }

    public function onMoveUserGroup(MoveUserGroupEvent $event): void
    {
        if (!$this->auditService->isConfigured(self::class)) {
            return;
        }

        $this->infos = [
            'userGroupId' => $event->getUserGroup()->id,
            'newParentId' => $event->getNewParent()->id,
        ];

        $this->auditService->log($this);
    }
}
