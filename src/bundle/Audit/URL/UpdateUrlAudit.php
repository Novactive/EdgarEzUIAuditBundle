<?php

namespace Edgar\EzUIAuditBundle\Audit\URL;

use Edgar\EzUIAudit\Audit\AbstractAudit;
use Ibexa\Contracts\Core\Repository\Events\URL\UpdateUrlEvent;

class UpdateUrlAudit extends AbstractAudit
{
    public static function getSubscribedEvents()
    {
        return [
            UpdateUrlEvent::class => ['onUpdateUrl', 0],
        ];
    }

    public function onUpdateUrl(UpdateUrlEvent $event): void
    {
        if (!$this->auditService->isConfigured(self::class)) {
            return;
        }

        $this->infos = [
            'urlId' => $event->getUrl()->id,
        ];

        $this->auditService->log($this);
    }
}
