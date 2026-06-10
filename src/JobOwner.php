<?php

namespace Nexph\Lifecycle;

class JobOwner extends AbstractOwner
{
    private mixed $job;
    
    public function __construct(mixed $job = null, ?OwnerScope $parent = null)
    {
        parent::__construct($parent);
        $this->job = $job;
    }
    
    public function spawn(callable $fn): ChildFiberOwner
    {
        return (new ChildFiberOwner($this))->spawn(fn($ctx) => $fn($ctx));
    }
}
