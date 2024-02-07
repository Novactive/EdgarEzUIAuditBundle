<?php

namespace Edgar\EzUIAuditBundle\Audit\ContentType;

use Edgar\EzUIAudit\Audit\AbstractAudit;
use Ibexa\Contracts\Core\Repository\Events\ContentType\AddFieldDefinitionEvent;

class AddFieldDefinitionAudit extends AbstractAudit
{
    public static function getSubscribedEvents()
    {
        return [
            AddFieldDefinitionEvent::class => ['onAddFieldDefinition', 0],
        ];
    }

    public function onAddFieldDefinition(AddFieldDefinitionEvent $event): void
    {
        if (!$this->auditService->isConfigured(self::class)) {
            return;
        }

        $this->infos = [
            'contentTypeDraftId' => $event->getContentTypeDraft()->id,
        ];
        $this->auditService->log($this);
    }
}
