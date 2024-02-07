<?php

namespace Edgar\EzUIAuditBundle\Audit\Content;

use Edgar\EzUIAudit\Audit\AbstractAudit;
use Ibexa\Contracts\Core\Repository\Events\Content\CreateContentEvent;

class CreateContentAudit extends AbstractAudit
{
    public static function getSubscribedEvents()
    {
        return [
            CreateContentEvent::class => ['onCreateContent', 0],
        ];
    }

    public function onCreateContent(CreateContentEvent $event): void
    {
        if (!$this->auditService->isConfigured(self::class)) {
            return;
        }

        $this->infos = [
            'versionNo' => $event->getContent()->getVersionInfo()->versionNo,
            'contentId' => $event->getContent()->id,
        ];
        $this->auditService->log($this);
    }
}
