<?php

namespace App\Service;

use PHPUnit\Framework\Constraint\IsInstanceOf;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;

class ServiceExceptionData
{

    public function __construct(protected int $statusCode, protected string $type, protected $data) {}

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
    public function getType(): string
    {
        return $this->type;
    }
    public function toArray(): array
    {
        $errors = [];
        foreach ($this->data as $key => $value) {
            if ($value instanceof ConstraintViolation) {
                $errors[] = [
                    'propertyPath' => $value->getPropertyPath(),
                    'description' => $value->getMessage()
                ];
            } else {

                $errors[] = [
                    'type' => $value
                ];
            }
        }
        return $errors;
    }
}
