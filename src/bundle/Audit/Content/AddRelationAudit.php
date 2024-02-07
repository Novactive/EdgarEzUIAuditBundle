<?php

namespace Edgar\EzUIAuditBundle\Audit\Content;

use Edgar\EzUIAudit\Audit\AbstractAudit;
use Ibexa\Contracts\Core\Repository\Events\Content\AddRelationEvent;

class AddRelationAudit extends AbstractAudit
{
    public static function getSubscribedEvents()
    {
        return [
            AddRelationEvent::class => ['onAddRelation', 0],
        ];
    }

    public function onAddRelation(AddRelationEvent $event): void
    {

        if (!$this->auditService->isConfigured(self::class)) {
            return;
        }

        $this->infos = [
            'dstContentId' => $event->getDestinationContent()->id,
            'srcContentId' => $event->getRelation()->sourceContentInfo->id,
            'srcVersionNo' => $event->getRelation()->sourceContentInfo->currentVersionNo,
        ];
        $this->auditService->log($this);
    }
}
