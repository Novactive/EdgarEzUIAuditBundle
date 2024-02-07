<?php

namespace Edgar\EzUIAuditBundle\Audit\URLWildcard;

use Edgar\EzUIAudit\Audit\AbstractAudit;
use Ibexa\Contracts\Core\Repository\Events\URLWildcard\TranslateEvent;

class TranslateAudit extends AbstractAudit
{
    public static function getSubscribedEvents()
    {
        return [
            TranslateEvent::class => ['onTranslateEvent', 0],
        ];
    }

    public function onTranslateEvent(TranslateEvent $event): void
    {
        if (!$this->auditService->isConfigured(self::class)) {
            return;
        }

        $this->infos = [
            'url' => $event->getUrl(),
        ];

        $this->auditService->log($this);
    }
}
