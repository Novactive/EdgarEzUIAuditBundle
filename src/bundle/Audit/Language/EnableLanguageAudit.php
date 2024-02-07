<?php

namespace Edgar\EzUIAuditBundle\Audit\Language;

use Edgar\EzUIAudit\Audit\AbstractAudit;
use Ibexa\Contracts\Core\Repository\Events\Language\EnableLanguageEvent;

class EnableLanguageAudit extends AbstractAudit
{
    public static function getSubscribedEvents()
    {
        return [
            EnableLanguageEvent::class => ['onEnableLanguage', 0],
        ];
    }

    public function onEnableLanguage(EnableLanguageEvent $event): void
    {
        if (!$this->auditService->isConfigured(self::class)) {
            return;
        }

        $this->infos = [
            'languageId' => $event->getLanguage()->id
        ];
        $this->auditService->log($this);
    }
}
