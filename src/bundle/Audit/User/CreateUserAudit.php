<?php

namespace Edgar\EzUIAuditBundle\Audit\User;

use Edgar\EzUIAudit\Audit\AbstractAudit;
use Ibexa\Contracts\Core\Repository\Events\User\CreateUserEvent;

class CreateUserAudit extends AbstractAudit
{
    public static function getSubscribedEvents()
    {
        return [
            CreateUserEvent::class => ['onCreateUser', 0],
        ];
    }

    public function onCreateUser(CreateUserEvent $event): void
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
