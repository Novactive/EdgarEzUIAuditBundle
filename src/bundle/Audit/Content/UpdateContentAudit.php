<?php

namespace Edgar\EzUIAuditBundle\Audit\Content;

use Edgar\EzUIAudit\Audit\AbstractAudit;
use Ibexa\Contracts\Core\Repository\Events\Content\UpdateContentEvent;

class UpdateContentAudit extends AbstractAudit
{

    public static function getSubscribedEvents()
    {
        return [
            UpdateContentEvent::class => ['onUpdateContent', 0],
        ];
    }

    public function onUpdateContent(UpdateContentEvent $event): void
    {
        if (!$this->auditService->isConfigured(self::class)) {
            return;
        }

        $this->infos = [
            'contentId' => $event->getContent()->id,
            'versionNo' => $event->getVersionInfo()->versionNo,
        ];
        $this->auditService->log($this);
    }

}
