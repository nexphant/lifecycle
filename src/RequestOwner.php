<?php

namespace Nexph\Lifecycle;

class RequestOwner extends AbstractOwner
{
    public function spawn(callable $fn): ChildFiberOwner
    {
        return (new ChildFiberOwner($this))->spawn(fn($ctx) => $fn($ctx));
    }
}
