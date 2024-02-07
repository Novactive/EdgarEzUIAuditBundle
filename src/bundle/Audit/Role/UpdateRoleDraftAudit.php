<?php

namespace Edgar\EzUIAuditBundle\Audit\Role;

use Edgar\EzUIAudit\Audit\AbstractAudit;
use Ibexa\Contracts\Core\Repository\Events\Role\UpdateRoleDraftEvent;

class UpdateRoleDraftAudit extends AbstractAudit
{
    public static function getSubscribedEvents()
    {
        return [
            UpdateRoleDraftEvent::class => ['onUpdateRoleDraft', 0],
        ];
    }

    public function onUpdateRoleDraft(UpdateRoleDraftEvent $event): void
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
