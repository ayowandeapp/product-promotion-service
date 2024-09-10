<?php

namespace App\EventListener;

use App\Service\ServiceException;
use App\Service\ServiceExceptionData;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class ExceptionListener
{

    public function onKernelException(ExceptionEvent $e)
    {
        $exception = $e->getThrowable();


        if ($exception instanceof ServiceException) {
            $exceptionData = $exception->getExceptionData();
        } else {
            $exceptionData = new ServiceExceptionData(500, '', [$exception->getMessage()]);
        }

        // dd($exceptionData->toArray());
        $res = new JsonResponse($exceptionData->toArray());
        $e->setResponse($res);
    }
}
