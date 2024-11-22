<?php

namespace App\Support\EventListener;

use App\Exceptions\MessageException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class ExceptionListener
{
    public function __invoke(ExceptionEvent $event): void
    {
        $thrownEvent = $event->getThrowable();

        $response = new Response();
        if (!is_subclass_of($thrownEvent, MessageException::class)) {
            // To do after completed -- Display generec server Error
            // $response->setStatusCode(SERVER_)
            return;
        }

        $jsonResponse = json_encode(
            [
                'success' => false,
                'message' => 'There was an issue while processing the request.',
                'errors' => $thrownEvent->getErrorList()
            ]
        );

        $response->headers->set('Content-Type', 'application/json');
        $response->setStatusCode($thrownEvent->getCode());
        $response->setContent($jsonResponse);

        $event->setResponse($response);
    }
}
