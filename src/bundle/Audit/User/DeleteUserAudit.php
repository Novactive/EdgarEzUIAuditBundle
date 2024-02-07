<?php

namespace Edgar\EzUIAuditBundle\Audit\User;

use Edgar\EzUIAudit\Audit\AbstractAudit;
use Ibexa\Contracts\Core\Repository\Events\User\DeleteUserEvent;

class DeleteUserAudit extends AbstractAudit
{
    public static function getSubscribedEvents()
    {
        return [
            DeleteUserEvent::class => ['onDeleteUser', 0],
        ];
    }

    public function onDeleteUser(DeleteUserEvent $event): void
    {
        if (!$this->auditService->isConfigured(self::class)) {
            return;
        }

        $this->infos = [
            'userId' => $event->getUser()->getUserId(),
            'affectedLocationIds' => $event->getLocations(),
        ];

        $this->auditService->log($this);
    }
}
