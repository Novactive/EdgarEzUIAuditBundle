<?php

namespace Edgar\EzUIAuditBundle\Audit\Location;

use Edgar\EzUIAudit\Audit\AbstractAudit;
use Ibexa\Contracts\Core\Repository\Events\Location\DeleteLocationEvent;

class DeleteLocationAudit extends AbstractAudit
{
    public static function getSubscribedEvents()
    {
        return [
            DeleteLocationEvent::class => ['onDeleteLocation', 0],
        ];
    }

    public function onDeleteLocation(DeleteLocationEvent $event): void
    {
        try {
            if (!$this->auditService->isConfigured(self::class)) {
                return;
            }

            $this->infos = [
                'parentLocationId' => $event->getLocation()->parentLocationId,
                'locationId' => $event->getLocation()->id,
                'contentId' => $event->getLocation()->contentId,
            ];
            $this->auditService->log($this);
            dd("EEEEEEEEEE",$this);

        }
        catch (\Exception $e){
            dd($e->getMessage());
        }
    }
}
