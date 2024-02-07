<?php

namespace Edgar\EzUIAuditBundle\Audit\ContentType;

use Edgar\EzUIAudit\Audit\AbstractAudit;;
use Ibexa\Contracts\Core\Repository\Events\ContentType\RemoveFieldDefinitionEvent;

class RemoveFieldDefinitionAudit extends AbstractAudit
{
    public static function getSubscribedEvents()
    {
        return [
            RemoveFieldDefinitionEvent::class => ['onRemoveFieldDefinition', 0],
        ];
    }

    public function onRemoveFieldDefinition(RemoveFieldDefinitionEvent $event): void
    {
        if (!$this->auditService->isConfigured(self::class)) {
            return;
        }

        $this->infos = [
            'contentTypeDraftId' => $event->getContentTypeDraft()->id,
            'fieldDefinitionId' => $event->getFieldDefinition()->id
        ];
        $this->auditService->log($this);
    }
}
