<?php

namespace Edgar\EzUIAuditBundle\Audit\Language;

use Edgar\EzUIAudit\Audit\AbstractAudit;
use Ibexa\Contracts\Core\Repository\Events\Language\UpdateLanguageNameEvent;

class UpdateLanguageNameAudit extends AbstractAudit
{
    public static function getSubscribedEvents()
    {
        return [
            UpdateLanguageNameEvent::class => ['onUpdateLanguageName', 0],
        ];
    }

    public function onUpdateLanguageName(UpdateLanguageNameEvent $event): void
    {
        if (!$this->auditService->isConfigured(self::class)) {
            return;
        }

        $this->infos = [
            'languageId' => $event->getLanguage()->id,
            'newName' => $event->getLanguage()->getName(),
        ];

        $this->auditService->log($this);
    }
}
