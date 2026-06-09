<?php

namespace Nexph\Lifecycle;

class ChildFiberOwner implements Owner
{
    private array $resources = [];
    private bool $closed = false;
    private ?Owner $parent;

    public function __construct(?Owner $parent = null)
    {
        $this->parent = $parent;
    }

    public function own(mixed $resource): void
    {
        if ($this->closed) {
            throw new \RuntimeException('Owner closed');
        }
        if ($this->parent && $this->parent->isClosed()) {
            throw new \RuntimeException('Parent owner closed');
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
