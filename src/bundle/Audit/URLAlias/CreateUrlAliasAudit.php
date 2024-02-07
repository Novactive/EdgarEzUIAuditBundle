<?php

namespace Edgar\EzUIAuditBundle\Audit\URLAlias;

use Edgar\EzUIAudit\Audit\AbstractAudit;
use Ibexa\Contracts\Core\Repository\Events\URLAlias\CreateUrlAliasEvent;

class CreateUrlAliasAudit extends AbstractAudit
{
    public static function getSubscribedEvents()
    {
        return [
            CreateUrlAliasEvent::class => ['onCreateUrlAlias', 0],
        ];
    }

    public function onCreateUrlAlias(CreateUrlAliasEvent $event): void
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
