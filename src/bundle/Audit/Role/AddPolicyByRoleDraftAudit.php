<?php

namespace Edgar\EzUIAuditBundle\Audit\Role;

use Edgar\EzUIAudit\Audit\AbstractAudit;
use Ibexa\Contracts\Core\Repository\Events\Role\AddPolicyByRoleDraftEvent;

class AddPolicyByRoleDraftAudit extends AbstractAudit
{
    public static function getSubscribedEvents()
    {
        return [
            AddPolicyByRoleDraftEvent::class => ['onAddPolicyByRoleDraft', 0],
        ];
    }

    public function onAddPolicyByRoleDraft(AddPolicyByRoleDraftEvent $event): void
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
