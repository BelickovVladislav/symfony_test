<?php


namespace App\EventSubscriber;


use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ExceptionSubscriber implements EventSubscriberInterface
{
    public function onKernelException(ResponseEvent $event)
    {
        if (!$event->getResponse()->isOk() && !($event->getResponse() instanceof JsonResponse)) {
            $event->setResponse(new JsonResponse(['error' => $event->getResponse()->getContent()], $event->getResponse()->getStatusCode()));
        }
    }

    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::RESPONSE => 'onKernelException',
        ];
    }
}
