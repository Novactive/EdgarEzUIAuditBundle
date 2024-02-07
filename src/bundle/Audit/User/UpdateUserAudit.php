<?php

namespace Edgar\EzUIAuditBundle\Audit\User;

use Edgar\EzUIAudit\Audit\AbstractAudit;
use Ibexa\Contracts\Core\Repository\Events\User\UpdateUserEvent;

class UpdateUserAudit extends AbstractAudit
{
    public static function getSubscribedEvents()
    {
        return [
            UpdateUserEvent::class => ['onUpdateUser', 0],
        ];
    }

    public function onUpdateUser(UpdateUserEvent $event): void
    {
        if (!$this->auditService->isConfigured(self::class)) {
            return;
        }

        $this->infos = [
            'userId' => $event->getUser()->getUserId(),
        ];

        $this->auditService->log($this);
    }
}
