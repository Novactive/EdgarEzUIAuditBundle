<?php

namespace Edgar\EzUIAuditBundle\Service;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\QueryBuilder;
use Edgar\EzUIAudit\Audit\AuditInterface;
use Edgar\EzUIAudit\Form\Data\AuditData;
use Edgar\EzUIAudit\Form\Data\ConfigureAuditData;
use Edgar\EzUIAudit\Form\Data\ExportAuditData;
use Edgar\EzUIAudit\Form\Data\FilterAuditData;
use Edgar\EzUIAudit\Handler\AuditHandler;
use Edgar\EzUIAudit\Repository\EdgarEzAuditConfigurationRepository;
use Edgar\EzUIAudit\Repository\EdgarEzAuditExportRepository;
use Edgar\EzUIAudit\Repository\EdgarEzAuditLogRepository;
use Edgar\EzUIAuditBundle\Entity\EdgarEzAuditConfiguration;
use Edgar\EzUIAuditBundle\Entity\EdgarEzAuditExport;
use Edgar\EzUIAuditBundle\Entity\EdgarEzAuditLog;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class AuditService
{
    /** @var AuditHandler */
    protected $auditHandler;

    /** @var TokenStorage */
    protected $tokenStorage;

    /** @var EdgarEzAuditConfigurationRepository */
    protected $auditConfiguration;

    /** @var EdgarEzAuditLogRepository */
    protected $auditLog;

    /** @var EdgarEzAuditExportRepository */
    protected $auditExport;

    /**
     * AuditService constructor.
     *
     * @param AuditHandler $auditHandler
     * @param Registry $doctrineRegistry
     * @param TokenStorage $tokenStorage
     */
    public function __construct(
        AuditHandler $auditHandler,
        Registry $doctrineRegistry,
        TokenStorage $tokenStorage
    ) {
        $this->auditHandler = $auditHandler;
        $this->tokenStorage = $tokenStorage;
        $entityManager = $doctrineRegistry->getManager();
        $this->auditConfiguration = $entityManager->getRepository(EdgarEzAuditConfiguration::class);
        $this->auditLog = $entityManager->getRepository(EdgarEzAuditLog::class);
        $this->auditExport = $entityManager->getRepository(EdgarEzAuditExport::class);
    }

    /**
     * Load Audit type groups.
     *
     * @return array
     */
    public function loadAuditTypeGroups(): array
    {
        $audits = $this->auditHandler->getAudits();
        $auditGroups = array_keys($audits);
        ksort($auditGroups);

        return $auditGroups;
    }

    /**
     * Load audit types by group.
     *
     * @param string $auditTypeGroup
     *
     * @return array
     */
    public function loadAuditTypes(string $auditTypeGroup): array
    {
        $audits = $this->auditHandler->getAudits();

        $return = [];
        foreach ($audits[$auditTypeGroup] as $audit) {
            $classInfos = explode('\\', get_class($audit));
            $classIdentifier = $classInfos[count($classInfos) - 1];
            $className = str_replace('Audit', '', $classIdentifier);
            $groupName = $classInfos[count($classInfos) - 2];

            $auditData = new AuditData();
            $auditData->setIdentifier($groupName . '/' . $classIdentifier);
            $auditData->setName(preg_replace('/(?<!^)([A-Z])/', ' \\1', $className));
            $return[] = $auditData;
        }

        return $return;
    }

    /**
     * Get audit configuration.
     *
     * @return ConfigureAuditData
     */
    public function getAuditConfiguration(): ConfigureAuditData
    {
        /** @var EdgarEzAuditConfiguration[] $auditConfigurations */
        $auditConfigurations = $this->auditConfiguration->findAll();

        $configureAuditData = new ConfigureAuditData();

        if ($auditConfigurations && count($auditConfigurations)) {
            $configureAuditData->setAuditTypes($auditConfigurations[0]->getAudits());
        }

        return $configureAuditData;
    }

    /**
     * Save audit configuration.
     *
     * @param array $audits
     *
     * @throws ORMException
     */
    public function saveAuditConfiguration(array $audits)
    {
        /** @var EdgarEzAuditConfiguration[] $auditConfigurations */
        $auditConfigurations = $this->auditConfiguration->findAll();

        $auditConfiguration = new EdgarEzAuditConfiguration();
        if ($auditConfigurations && count($auditConfigurations)) {
            $auditConfiguration = $auditConfigurations[0];
        }
        $auditConfiguration->setAudits($audits);

        try {
            $this->auditConfiguration->save($auditConfiguration);
        } catch (ORMException $e) {
            throw $e;
        }
    }

    /**
     * Check if audit is configured.
     *
     * @param string $classPath
     *
     * @return bool
     */
    public function isConfigured(string $classPath): bool
    {
        $auditConfiguration = $this->getAuditConfiguration();
        /** @var AuditData[] $audits */
        $audits = $auditConfiguration->getAuditTypes();

        $classInfos = explode('\\', $classPath);
        $className = $classInfos[count($classInfos) - 1];
        $groupName = $classInfos[count($classInfos) - 2];
        foreach ($audits as $audit) {
            if ($audit->getIdentifier() == $groupName . '/' . $className) {
                return true;
            }
        }

        return false;
    }

    /**
     * Log audit informations.
     *
     * @param AuditInterface $audit
     */
    public function log(AuditInterface $audit)
    {
        $user = $this->tokenStorage->getToken()->getUser();
        $apiUser = $user->getAPIUser();

        try {
            $this->auditLog->log($apiUser->id, $audit->getGroup(), $audit->getIdentifier(), $audit->getName(), $audit->getInfos());
        } catch (ORMException $e) {
        }
    }

    /**
     * @param FilterAuditData $data
     *
     * @return QueryBuilder
     */
    public function buildLogQuery(FilterAuditData $data): QueryBuilder
    {
        return $this->auditLog->buildQuery($data);
    }

    /**
     * @return QueryBuilder
     */
    public function buildExportQuery(): QueryBuilder
    {
        return $this->auditExport->buildQuery();
    }

    /**
     * @return QueryBuilder
     */
    public function buildExportQueryForUser($userId): QueryBuilder
    {
        return $this->auditExport->buildExportQueryForUser($userId);
    }

    /**
     * @param ExportAuditData $data
     */
    public function saveExport(ExportAuditData $data)
    {
        $user = $this->tokenStorage->getToken()->getUser();
        $apiUser = $user->getAPIUser();

        try {
            $this->auditExport->save($data, $apiUser->id);
        } catch (ORMException $e) {
        }
    }
}
