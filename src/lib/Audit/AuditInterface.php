<?php

namespace Edgar\EzUIAudit\Audit;

use eZ\Publish\Core\SignalSlot\Signal;

interface AuditInterface
{
    public static function getSubscribedEvents();

    public function getInfos(): array;

    public function getIdentifier(): string;

    public function getName(): string;

    public function getGroup(): string;
}
