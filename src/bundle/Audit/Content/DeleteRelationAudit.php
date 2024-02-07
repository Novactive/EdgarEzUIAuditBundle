<?php

namespace Edgar\EzUIAuditBundle\Audit\Content;

use Edgar\EzUIAudit\Audit\AbstractAudit;
use Ibexa\Contracts\Core\Repository\Events\Content\DeleteRelationEvent;

class DeleteRelationAudit extends AbstractAudit
{
    public static function getSubscribedEvents()
    {
        return [
            DeleteRelationEvent::class => ['onDeleteRelation', 0],
        ];
    }

    public function onDeleteRelation(DeleteRelationEvent $event): void
    {
        if (!$this->auditService->isConfigured(self::class)) {
            return;
        }

        $this->infos = [
            'srcVersionNo' => $event->getSourceVersion()->versionNo,
            'srcContentId' => $event->getSourceVersion()->contentInfo->id,
            'dstContentId' => $event->getDestinationContent()->id,
        ];
        $this->auditService->log($this);
    }
}
