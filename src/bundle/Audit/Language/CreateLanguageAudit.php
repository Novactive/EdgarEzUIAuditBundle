<?php

namespace Edgar\EzUIAuditBundle\Audit\Language;

use Edgar\EzUIAudit\Audit\AbstractAudit;
use Ibexa\Contracts\Core\Repository\Events\Language\CreateLanguageEvent;

class CreateLanguageAudit extends AbstractAudit
{
    public static function getSubscribedEvents()
    {
        return [
            CreateLanguageEvent::class => ['onCreateLanguage', 0],
        ];
    }

    public function onCreateLanguage(CreateLanguageEvent $event): void
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
