<?php

namespace Edgar\EzUIAuditBundle\Audit\Role;

use Edgar\EzUIAudit\Audit\AbstractAudit;
use Ibexa\Contracts\Core\Repository\Events\Role\RemovePolicyByRoleDraftEvent;

class RemovePolicyByRoleDraftAudit extends AbstractAudit
{
    public static function getSubscribedEvents()
    {
        return [
            RemovePolicyByRoleDraftEvent::class => ['onRemovePolicyByRoleDraft', 0],
        ];
    }

    public function onRemovePolicyByRoleDraft(RemovePolicyByRoleDraftEvent $event): void
    {
        if (!$this->auditService->isConfigured(self::class)) {
            return;
        }

        $this->infos = [
            'roleId' => $event->getRoleDraft()->id,
            'policyId' => $event->getPolicyDraft()->id,
        ];

        $this->auditService->log($this);
    }
}
