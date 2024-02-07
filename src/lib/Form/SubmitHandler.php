<?php

namespace Edgar\EzUIAudit\Form;

use Ibexa\AdminUi\Notification\FlashBagNotificationHandler;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;

class SubmitHandler
{
    /** @var FlashBagNotificationHandler $notificationHandler */
    protected $notificationHandler;

    /** @var RouterInterface $router */
    protected $router;

    /**
     * @param FlashBagNotificationHandler $notificationHandler
     * @param RouterInterface $router
     */
    public function __construct(FlashBagNotificationHandler $notificationHandler, RouterInterface $router)
    {
        $this->notificationHandler = $notificationHandler;
        $this->router = $router;
    }

    /**
     * Wraps business logic with reusable boilerplate code.
     *
     * Handles form errors (NotificationHandler:warning).
     * Handles business logic exceptions (NotificationHandler:error).
     *
     * @param FormInterface $form
     * @param callable(mixed):array $handler
     *
     * @return null|Response
     */
    public function handle(FormInterface $form, callable $handler): ?Response
    {
        $data = $form->getData();

        if ($form->isValid()) {
            try {
                $result = $handler($data);

                if ($result instanceof Response) {
                    return $result;
                }
            } catch (\Exception $e) {
                $this->notificationHandler->error($e->getMessage());
            }
        } else {
            foreach ($form->getErrors(true, true) as $formError) {
                $this->notificationHandler->warning($formError->getMessage());
            }
        }

        return null;
    }
}
