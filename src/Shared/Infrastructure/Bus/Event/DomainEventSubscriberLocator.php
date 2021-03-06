<?php

declare(strict_types=1);

namespace TechyCode\Shared\Infrastructure\Bus\Event;

use TechyCode\Shared\Infrastructure\Bus\CallableFirstParameterExtractor;
use Traversable;

final class DomainEventSubscriberLocator
{
    private array $mapping;

    public function __construct(Traversable $mapping)
    {
        $this->mapping = iterator_to_array($mapping);
    }

    public function allSubscribedTo(string $eventClass): callable
    {
        $formatted = CallableFirstParameterExtractor::forPipedCallables($this->mapping);

        return $formatted[$eventClass];
    }

    // TODO: Come back for Kafka implementation withKafkaQueueNamed

    public function all(): array
    {
        return $this->mapping;
    }
}