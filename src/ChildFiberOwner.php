<?php

namespace Nexph\Lifecycle;

class ChildFiberOwner extends AbstractOwner
{
    public function __construct(?OwnerScope $parent = null)
    {
        parent::__construct($parent);
    }

    public function own(mixed $resource): mixed
    {
        if ($this->parent?->isClosed()) {
            throw new \RuntimeException('Parent owner closed');
        }
        return parent::own($resource);
    }
}
