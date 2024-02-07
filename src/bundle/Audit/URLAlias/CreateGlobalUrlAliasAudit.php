<?php

namespace Edgar\EzUIAuditBundle\Audit\URLAlias;

use Edgar\EzUIAudit\Audit\AbstractAudit;
use Ibexa\Contracts\Core\Repository\Events\URLAlias\CreateGlobalUrlAliasEvent;

class CreateGlobalUrlAliasAudit extends AbstractAudit
{
    public static function getSubscribedEvents()
    {
        return [
            CreateGlobalUrlAliasEvent::class => ['onCreateGlobalUrlAlias', 0],
        ];
    }

    public function onCreateGlobalUrlAlias(CreateGlobalUrlAliasEvent $event): void
    {
        if (!$this->auditService->isConfigured(self::class)) {
            return;
        }

        $this->infos = [
            'urlAliasId' => $event->getUrlAlias()->id,
        ];

        $this->auditService->log($this);
    }
}
