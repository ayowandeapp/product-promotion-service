<?php

namespace App\Service;

use Symfony\Component\HttpKernel\Exception\HttpException;

class ServiceException extends HttpException
{
    private ServiceExceptionData $eData;
    public function __construct(ServiceExceptionData $eData)
    {
        $statusCode = $eData->getStatusCode();

        $type = $eData->getType();

        parent::__construct($statusCode);
        $this->eData = $eData;
    }

    public function getExceptionData(): ServiceExceptionData
    {
        return $this->eData;
    }
}
