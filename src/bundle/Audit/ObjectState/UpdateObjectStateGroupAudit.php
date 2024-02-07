<?php

namespace Edgar\EzUIAuditBundle\Audit\ObjectState;

use Edgar\EzUIAudit\Audit\AbstractAudit;
use Ibexa\Contracts\Core\Repository\Events\ObjectState\UpdateObjectStateGroupEvent;

class UpdateObjectStateGroupAudit extends AbstractAudit
{
    public static function getSubscribedEvents()
    {
        return [
            UpdateObjectStateGroupEvent::class => ['onUpdateObjectStateGroup', 0],
        ];
    }

    public function onUpdateObjectStateGroup(UpdateObjectStateGroupEvent $event): void
    {
        if (!$this->auditService->isConfigured(self::class)) {
            return;
        }

        $this->infos = [
            'objectStateGroupId' => $event->getObjectStateGroup()->id,
        ];

        $this->auditService->log($this);
    }
}
