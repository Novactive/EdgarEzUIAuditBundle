<?php

namespace Edgar\EzUIAuditBundle\Controller;

use Doctrine\Persistence\ManagerRegistry;
use Edgar\EzUIAudit\Form\Data\ExportAuditData;
use Edgar\EzUIAudit\Form\Factory\ExportFormFactory;
use Edgar\EzUIAudit\Form\Mapper\PagerContentToExportMapper;
use Edgar\EzUIAudit\Form\SubmitHandler;
use Edgar\EzUIAuditBundle\Entity\EdgarEzAuditExport;
use Edgar\EzUIAuditBundle\Service\AuditService;
use Ibexa\Contracts\Core\Repository\PermissionResolver;
use Ibexa\Core\Repository\SiteAccessAware\Repository;
use Ibexa\AdminUi\Notification\FlashBagNotificationHandler;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;

class AuditExportController extends BaseController
{
    /** @var PagerContentToExportMapper */
    protected $pagerContentToExportMapper;

    /** @var ExportFormFactory */
    protected $exportFormFactory;

    /** @var SubmitHandler */
    protected $submitHandler;

    protected $exportRepository;
    /** @var LoggerInterface */
    protected $logger;

    /** @var Repository */
    protected $repository;

    /** @var KernelInterface */
    protected $kernel;

    /**
     * @param AuditService $auditService
     * @param PermissionResolver $permissionResolver
     * @param FlashBagNotificationHandler $notificationHandler
     * @param TranslatorInterface $translator
     * @param PagerContentToExportMapper $pagerContentToExportMapper
     * @param ExportFormFactory $exportFormFactory
     * @param SubmitHandler $submitHandler
     * @param ManagerRegistry $doctrineRegistry
     * @param LoggerInterface $logger
     * @param Repository $repository
     * @param KernelInterface $kernel
     */
    public function __construct(
        AuditService                $auditService,
        PermissionResolver          $permissionResolver,
        FlashBagNotificationHandler $notificationHandler,
        TranslatorInterface         $translator,
        PagerContentToExportMapper  $pagerContentToExportMapper,
        ExportFormFactory           $exportFormFactory,
        SubmitHandler               $submitHandler,
        ManagerRegistry             $doctrineRegistry,
        LoggerInterface             $logger,
        Repository                  $repository,
        KernelInterface             $kernel
    )
    {
        parent::__construct($auditService, $permissionResolver, $notificationHandler, $translator);
        $this->auditService = $auditService;
        $this->permissionResolver = $permissionResolver;
        $this->notificationHandler = $notificationHandler;
        $this->translator = $translator;
        $this->pagerContentToExportMapper = $pagerContentToExportMapper;
        $this->exportFormFactory = $exportFormFactory;
        $this->submitHandler = $submitHandler;
        $entityManager = $doctrineRegistry->getManager();
        $this->exportRepository = $entityManager->getRepository(EdgarEzAuditExport::class);
        $this->logger = $logger;
        $this->repository = $repository;
        $this->kernel = $kernel;
    }

    /**
     * Export audit informations.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function exportAction(Request $request): Response
    {
        $this->permissionAccess('uiaudit', 'export');

        $limit = $request->get('limit', 10);
        $page = $request->get('page', 1);

        $query = $this->auditService->buildExportQuery();
        $limit        = $request->get('limit', 10);
        $page         = $request->get('page', 1);
        $currentUserId = $this->permissionResolver
            ->getCurrentUserReference()->getUserId();
        $currentUser = $this->repository->getUserService()->loadUser($currentUserId);

        $hasAllAccess = $this->permissionResolver->hasAccess('uiaudit', 'configure', $currentUser);
        if ($hasAllAccess) {
            $query = $this->auditService->buildExportQuery();
        } else {
            $query = $this->auditService->buildExportQueryForUser($this->repository->getCurrentUser()->id);
        }
        $pagerfanta = new Pagerfanta(
            new DoctrineORMAdapter($query)
        );

        $pagerfanta->setMaxPerPage($limit);
        $pagerfanta->setCurrentPage(min($page, $pagerfanta->getNbPages()));

        return $this->render('@EdgarEzUIAudit/audit/export.html.twig', [
            'exports' => $this->pagerContentToExportMapper->map($pagerfanta),
            'pager' => $pagerfanta,
        ]);
    }

    /**
     * Register audit export transaction.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function askExportAction(Request $request): Response
    {
        $this->permissionAccess('uiaudit', 'export');

        $exportAuditType = $this->exportFormFactory->exportAudit(
            new ExportAuditData()
        );
        $exportAuditType->handleRequest($request);

        if ($exportAuditType->isSubmitted()) {
            $result = $this->submitHandler->handle($exportAuditType, function (ExportAuditData $data) use ($exportAuditType) {
                $this->auditService->saveExport($data);

                return new RedirectResponse($this->generateUrl('edgar.audit.export', []));
            });

            if ($result instanceof Response) {
                return $result;
            }
        }

        return new RedirectResponse($this->generateUrl('edgar.audit.export', []));
    }

    /**
     * @return RedirectResponse
     */
    public function exportNowAction(): RedirectResponse
    {
        try {
            $application = new Application($this->kernel);
            $application->setAutoExit(false);
            $input = new ArrayInput([
                'command' => 'edgarez:export:all',
                '-d memory_limit' => '-1',
            ]);
            $output = new BufferedOutput();
            $application->run($input, $output);
            if ($output) {
                $this->logger->error($output->fetch());
            }

            return new RedirectResponse($this->generateUrl('edgar.audit.export', []));

        } catch (\Exception $exception) {
            return new RedirectResponse($this->generateUrl('edgar.audit.export', ['error' => $exception->getMessage()]));
        }
    }

    /**
     * @param $id
     * @return mixed
     */
    public function deleteLogAction($id)
    {
        try {
            $em              = $this->getDoctrine()->getManager();
            $auditExport     = $em->getRepository(EdgarEzAuditExport::class);
            $requestToDelete = $auditExport->find($id);
            $em->remove($requestToDelete);
            $em->flush();

            return $this->redirectToRoute('edgar.audit.export');
        } catch (\Exception $exception) {
            return $this->redirectToRoute('edgar.audit.export', ['error' => $exception->getMessage()]);
        }
    }
    
    /**
     * @param EdgarEzAuditExport $exportId
     *
     * @return Response
     */
    public function downloadAction(int $exportId, string $filename): Response
    {
        $this->permissionAccess('uiaudit', 'export');

        $export = $this->exportRepository->find($exportId);

        $filePath = $export->getFile();
        $response = new Response();
        $response->setContent(file_get_contents($filePath));
        $response->headers->set('Content-Type', 'text/csv; charset=utf-8');
        $response->headers->set('Content-Disposition','attachment; filename="' . $filename . '"');

        return $response;
    }
}
