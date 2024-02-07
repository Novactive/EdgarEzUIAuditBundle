<?php

namespace Edgar\EzUIAuditBundle\Audit\Language;

use Edgar\EzUIAudit\Audit\AbstractAudit;
use Ibexa\Contracts\Core\Repository\Events\Language\DisableLanguageEvent;

class DisableLanguageAudit extends AbstractAudit
{
    public static function getSubscribedEvents()
    {
        return [
            DisableLanguageEvent::class => ['onDisableLanguage', 0],
        ];
    }

    public function onDisableLanguage(DisableLanguageEvent $event): void
    {
        if (!$this->auditService->isConfigured(self::class)) {
            return;
        }

        $this->infos = [
            'languageId' => $event->getLanguage()->id,
        ];

        $this->auditService->log($this);
    }
}
