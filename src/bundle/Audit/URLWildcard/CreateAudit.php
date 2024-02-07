<?php

namespace Edgar\EzUIAuditBundle\Audit\URLWildcard;

use Edgar\EzUIAudit\Audit\AbstractAudit;
use Ibexa\Contracts\Core\Repository\Events\URLWildcard\CreateEvent;

class CreateAudit extends AbstractAudit
{
    public static function getSubscribedEvents()
    {
        return [
            CreateEvent::class => ['onCreate', 0],
        ];
    }

    public function onCreate(CreateEvent $event): void
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
