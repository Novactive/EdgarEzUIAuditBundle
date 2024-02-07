<?php

namespace Edgar\EzUIAuditBundle\Audit\ContentType;

use Edgar\EzUIAudit\Audit\AbstractAudit;
use Ibexa\Contracts\Core\Repository\Events\ContentType\UpdateFieldDefinitionEvent;

class UpdateFieldDefinitionAudit extends AbstractAudit
{
    public static function getSubscribedEvents()
    {
        return [
            UpdateFieldDefinitionEvent::class => ['onUpdateFieldDefinition', 0],
        ];
    }

    public function onUpdateFieldDefinition(UpdateFieldDefinitionEvent $event): void
    {
        if (!$this->auditService->isConfigured(self::class)) {
            return;
        }

        $this->infos = [
            'contentTypeDraftId' => $event->getContentTypeDraft()->id,
            'fieldDefinitionId' => $event->getFieldDefinition()->id,
        ];
        $this->auditService->log($this);
    }
}
