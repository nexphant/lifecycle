<?php

namespace Nexph\Lifecycle;

class RequestOwner implements Owner
{
    private array $resources = [];
    private bool $closed = false;
    private array $childFibers = [];

    public function own(mixed $resource): void
    {
        if ($this->closed) {
            throw new \RuntimeException('Owner closed');
        }
        $this->resources[] = $resource;
    }

    public function addChildFiber(mixed $fiber): void
    {
        $this->childFibers[] = $fiber;
    }

    public function close(): void
    {
        if ($this->closed) {
            return;
        }
        $this->closed = true;
        
        foreach ($this->childFibers as $fiber) {
            if (method_exists($fiber, 'cancel')) {
                $fiber->cancel();
            }
        }
        
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
