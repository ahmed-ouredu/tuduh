<?php

declare(strict_types=1);

namespace TechyCode\Shared\Infrastructure\Symfony;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use TechyCode\Shared\Domain\DomainError;
use TechyCode\Shared\Domain\Utils;
use Throwable;

final class ApiExceptionListener
{
    private $exceptionHandler;

    public function __construct(ApiExceptionsHttpStatusCodeMapping $exceptionHandler)
    {
        $this->exceptionHandler = $exceptionHandler;
    }

    public function onException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        $event->setResponse(
            new JsonResponse(
                [
                    'code'    => $this->exceptionCodeFor($exception),
                    'message' => $exception->getMessage(),
                ],
                $this->exceptionHandler->statusCodeFor(get_class($exception))
            )
        );
    }

    private function exceptionCodeFor(Throwable $error): string
    {
        $domainErrorClass = DomainError::class;

        return $error instanceof $domainErrorClass
            ? $error->errorCode()
            : Utils::toSnakeCase(Utils::getClassBasename($error));
    }
}