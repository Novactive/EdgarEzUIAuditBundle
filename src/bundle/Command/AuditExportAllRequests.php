<?php
namespace Edgar\EzUIAuditBundle\Command;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Internal\Hydration\IterableResult;
use Doctrine\ORM\ORMException;
use Edgar\Cron\Cron\AbstractCron;
use Edgar\Cron\Repository\EdgarCronRepository;
use Edgar\EzUIAuditBundle\Entity\EdgarEzAuditExport;
use Edgar\EzUIAuditBundle\Service\AuditService;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class AuditExportAllRequests extends AbstractCron
{
    const EXPORT_DIR = '_export';
    /** @var EdgarRepository */
    private $exportRepository;
    /** @var AuditService */
    private $auditService;
    /** @var string */
    private $kernelRootDir;
    /** @var string */
    private $varDir;
    /** @var string */
    private $storageDir;
    /** @var EntityManager */
    private $em;
    /**
     * AuditExportCron constructor.
     *
     * @param string|null   $name
     * @param AuditService  $auditService
     * @param string        $kernelRootDir
     * @param string        $varDir
     * @param string        $storageDir
     */
    public function __construct(
        ?string $name = null,
        AuditService $auditService,
        string $kernelRootDir,
        string $varDir,
        string $storageDir,
        EntityManager $em
    ) {
        parent::__construct($name);
        $this->em               = $em;
        $this->exportRepository = $em->getRepository(EdgarEzAuditExport::class);
        $this->auditService     = $auditService;
        $this->kernelRootDir    = $kernelRootDir;
        $this->varDir           = $varDir;
        $this->storageDir       = $storageDir;
    }
    /**
     * Configure cron.
     */
    protected function configure()
    {
        $this
            ->setName('edgarez:export:all')
            ->setDescription('Export All audit informations');
    }
    /**
     * Execute cron export.
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int|void|null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $exports = $this->exportRepository->startExportAll();
        if ($exports != null) {
            foreach ($exports as $export) {
                /** @var IterableResult $result */
                $result = $this->exportRepository->processExport($export);
                $data = [];
                while (false !== ($row = $result->next())) {
                    $arrayRow = (array) $row[0];
                    array_walk($arrayRow, function (&$exportData, $key) {
                        if ($exportData instanceof \DateTime) {
                            $exportData = $exportData->format('Y-m-d H:i:s');
                        } elseif (is_array($exportData)) {
                            array_walk($exportData, function (&$data, $key) {
                                $data = $key.': '.$data;
                            });
                            $exportData = implode("\n", $exportData);
                        }
                    });
                    $data[] = $arrayRow;
                }
                $now = new \DateTime();
                $exportDir = $this->kernelRootDir.'/../web/'.$this->varDir.'/'.$this->storageDir.'/'.self::EXPORT_DIR;
                if (!is_dir($exportDir)) {
                    if (!@mkdir($exportDir, 0777, true)) {
                        $output->writeln('Fail to create export directory');
                        try {
                            $this->exportRepository->setStatus($export, EdgarRepository::STATUS_KO);
                        } catch (ORMException $e) {
                            $output->writeln('Fail to create export directory');
                        }
                        return EdgarCronRepository::STATUS_ERROR;
                    }
                }
                $csvFile    = $exportDir.'/audit_export_'.$now->getTimestamp().'_'.$export->getId().'.csv';
                $serializer = new Serializer([new ObjectNormalizer()], [new CsvEncoder()]);
                file_put_contents(
                    $csvFile,
                    $serializer->encode($data, 'csv')
                );
                try {
                    $this->exportRepository->endExport($export, $csvFile);
                } catch (ORMException $e) {
                    $output->writeln('Fail to export audit: '.$e->getMessage());
                    try {
                        $this->exportRepository->setStatus($export, EdgarRepository::STATUS_KO);
                    } catch (ORMException $e) {
                        $output->writeln('Fail to export audit: '.$e->getMessage());
                    }
                    return EdgarCronRepository::STATUS_ERROR;
                }
            }
        }
    }
}