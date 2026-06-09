<?php

namespace Nexph\Lifecycle;

class RequestOwner extends AbstractOwner
{
    public function addChildFiber(mixed $fiber): void
    {
        $this->child($fiber);
    }
}
