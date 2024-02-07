<?php

namespace Edgar\EzUIAuditBundle\Audit\URLWildcard;

use Edgar\EzUIAudit\Audit\AbstractAudit;
use Ibexa\Contracts\Core\Repository\Events\URLWildcard\RemoveEvent;

class RemoveAudit extends AbstractAudit
{
    public static function getSubscribedEvents()
    {
        return [
            RemoveEvent::class => ['onRemoveEvent', 0],
        ];
    }

    public function onRemoveEvent(RemoveEvent $event): void
    {
        if (!$this->auditService->isConfigured(self::class)) {
            return;
        }

        $this->infos = [
            'urlWildcardId' => $event->getUrlWildcard()->id,
        ];

        $this->auditService->log($this);
    }
}
