<?php

namespace Edgar\EzUIAuditBundle\Audit\URLAlias;

use Edgar\EzUIAudit\Audit\AbstractAudit;
use Ibexa\Contracts\Core\Repository\Events\URLAlias\RemoveAliasesEvent;

class RemoveAliasesAudit extends AbstractAudit
{
    public static function getSubscribedEvents()
    {
        return [
            RemoveAliasesEvent::class => ['onRemoveAliases', 0],
        ];
    }

    public function onRemoveAliases(RemoveAliasesEvent $event): void
    {
        if (!$this->auditService->isConfigured(self::class)) {
            return;
        }

        $this->infos = [
        ];

        $this->auditService->log($this);
    }
}
