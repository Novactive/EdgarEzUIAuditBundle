<?php

namespace Edgar\EzUIAuditBundle\Audit\ObjectState;

use Edgar\EzUIAudit\Audit\AbstractAudit;
use Ibexa\Contracts\Core\Repository\Events\ObjectState\CreateObjectStateGroupEvent;

class CreateObjectStateGroupAudit extends AbstractAudit
{
    public static function getSubscribedEvents()
    {
        return [
            CreateObjectStateGroupEvent::class => ['onCreateObjectStateGroup', 0],
        ];
    }

    public function onCreateObjectStateGroup(CreateObjectStateGroupEvent $event): void
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
