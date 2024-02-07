<?php

namespace Edgar\EzUIAuditBundle\Audit\Language;

use Edgar\EzUIAudit\Audit\AbstractAudit;
use Ibexa\Contracts\Core\Repository\Events\Language\DeleteLanguageEvent;

class DeleteLanguageAudit extends AbstractAudit
{
    public static function getSubscribedEvents()
    {
        return [
            DeleteLanguageEvent::class => ['onDeleteLanguage', 0],
        ];
    }

    public function onDeleteLanguage(DeleteLanguageEvent $event): void
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
