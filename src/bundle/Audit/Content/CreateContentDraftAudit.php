<?php

namespace Edgar\EzUIAuditBundle\Audit\Content;

use Edgar\EzUIAudit\Audit\AbstractAudit;
use Ibexa\Contracts\Core\Repository\Events\Content\CreateContentDraftEvent;

class CreateContentDraftAudit extends AbstractAudit
{
    public static function getSubscribedEvents()
    {
        return [
            CreateContentDraftEvent::class => ['onCreateContentDraft', 0],
        ];
    }

    public function onCreateContentDraft(CreateContentDraftEvent $event): void
    {
        if (!$this->auditService->isConfigured(self::class)) {
            return;
        }

        $this->infos = [
            'contentId' => $event->getContentDraft()->id,
            'versionNo' => $event->getContentDraft()->versionInfo->versionNo,
            'userId' => $event->getVersionInfo()->creatorId,
        ];
        $this->auditService->log($this);
    }
}
