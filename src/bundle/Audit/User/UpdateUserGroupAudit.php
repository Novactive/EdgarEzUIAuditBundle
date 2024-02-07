<?php

namespace Edgar\EzUIAuditBundle\Audit\User;

use Edgar\EzUIAudit\Audit\AbstractAudit;
use Ibexa\Contracts\Core\Repository\Events\User\UpdateUserGroupEvent;

class UpdateUserGroupAudit extends AbstractAudit
{
    public static function getSubscribedEvents()
    {
        return [
            UpdateUserGroupEvent::class => ['onUpdateUserGroup', 0],
        ];
    }

    public function onUpdateUserGroup(UpdateUserGroupEvent $event): void
    {
        if (!$this->auditService->isConfigured(self::class)) {
            return;
        }

        $this->infos = [
            'userGroupId' => $event->getUserGroup()->id,
        ];

        $this->auditService->log($this);
    }
}
