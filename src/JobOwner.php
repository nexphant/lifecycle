<?php

namespace Nexph\Lifecycle;

class JobOwner implements Owner
{
    private array $resources = [];
    private bool $closed = false;

    public function own(mixed $resource): void
    {
        if ($this->closed) {
            throw new \RuntimeException('Owner closed');
        }
        $this->resources[] = $resource;
    }

    public function close(): void
    {
        if ($this->closed) {
            return;
        }
        $this->closed = true;
        while ($resource = array_pop($this->resources)) {
            if (method_exists($resource, 'close')) {
                $resource->close();
            }
        }
    }

    public function isClosed(): bool
    {
        return $this->closed;
    }
}
