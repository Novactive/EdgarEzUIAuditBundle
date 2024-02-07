<?php

namespace Edgar\EzUIAuditBundle\Audit\Content;

use Edgar\EzUIAudit\Audit\AbstractAudit;
use Ibexa\Contracts\Core\Repository\Events\Trash\TrashEvent;

class DeleteContentAudit extends AbstractAudit
{
    public static function getSubscribedEvents()
    {
        return [
            TrashEvent::class => ['onDeleteContent', 0],
        ];
    }

    public function onDeleteContent(TrashEvent $event): void
    {

        if (!$this->auditService->isConfigured(self::class)) {
            return;
        }

        $this->infos = [
            'contentId' => $event->getTrashItem()->contentId,
            'affectedLocationIds' => $event->getLocation()->id
        ];
        $this->auditService->log($this);
    }
}
