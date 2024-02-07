<?php

namespace Edgar\EzUIAuditBundle\Audit\Content;

use Edgar\EzUIAudit\Audit\AbstractAudit;
use Ibexa\Contracts\Core\Repository\Events\Content\PublishVersionEvent;

class PublishVersionAudit extends AbstractAudit
{
    public static function getSubscribedEvents()
    {
        return [
            PublishVersionEvent::class => ['onPublishVersion', 0],
        ];
    }

    public function onPublishVersion(PublishVersionEvent $event): void
    {
        if (!$this->auditService->isConfigured(self::class)) {
            return;
        }

        $this->infos = [
            'contentId' => $event->getContent()->id,
            'versionNo' => $event->getVersionInfo()->versionNo
        ];
        $this->auditService->log($this);
    }
}
