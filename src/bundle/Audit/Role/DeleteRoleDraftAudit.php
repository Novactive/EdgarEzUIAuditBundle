<?php

namespace Edgar\EzUIAuditBundle\Audit\Role;

use Edgar\EzUIAudit\Audit\AbstractAudit;
use Ibexa\Contracts\Core\Repository\Events\Role\DeleteRoleDraftEvent;

class DeleteRoleDraftAudit extends AbstractAudit
{
    public static function getSubscribedEvents()
    {
        return [
            DeleteRoleDraftEvent::class => ['onDeleteRoleDraft', 0],
        ];
    }

    public function onDeleteRoleDraft(DeleteRoleDraftEvent $event): void
    {
        if (!$this->auditService->isConfigured(self::class)) {
            return;
        }

        $this->infos = [
            'roleId' => $event->getRoleDraft()->id,
        ];

        $this->auditService->log($this);
    }
}
