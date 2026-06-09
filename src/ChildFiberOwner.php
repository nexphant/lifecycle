<?php

namespace Nexph\Lifecycle;

class ChildFiberOwner extends AbstractOwner
{
    private ?Owner $parent;

    public function __construct(?Owner $parent = null)
    {
        $this->parent = $parent;
    }

    public function own(mixed $resource): void
    {
        $this->assertOpen();
        if ($this->parent && $this->parent->isClosed()) {
            throw new \RuntimeException('Parent owner closed');
        }
        $this->resources[] = $resource;
    }
}
