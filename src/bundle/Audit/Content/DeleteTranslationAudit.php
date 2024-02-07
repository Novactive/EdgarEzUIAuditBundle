<?php

namespace Edgar\EzUIAuditBundle\Audit\Content;

use Edgar\EzUIAudit\Audit\AbstractAudit;
use Ibexa\Contracts\Core\Repository\Events\Content\DeleteTranslationEvent;

class DeleteTranslationAudit extends AbstractAudit
{
    public static function getSubscribedEvents()
    {
        return [
            DeleteTranslationEvent::class => ['onDeleteTranslation', 0],
        ];
    }

    public function onDeleteTranslation(DeleteTranslationEvent $event): void
    {
        if (!$this->auditService->isConfigured(self::class)) {
            return;
        }

        $this->infos = [
            'contentId' => $event->getContentInfo()->id,
            'languageCode' => $event->getLanguageCode(),
        ];
        $this->auditService->log($this);
    }
}
