<?php

namespace Edgar\EzUIAuditBundle\Audit\User;

use Edgar\EzUIAudit\Audit\AbstractAudit;
use Ibexa\Contracts\Core\Repository\Events\User\CreateUserGroupEvent;

class CreateUserGroupAudit extends AbstractAudit
{
    public static function getSubscribedEvents()
    {
        return [
            CreateUserGroupEvent::class => ['onCreateUserGroup', 0],
        ];
    }

    public function onCreateUserGroup(CreateUserGroupEvent $event): void
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
