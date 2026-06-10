<?php

namespace Nexph\Lifecycle;

class TaskOwner extends AbstractOwner
{
    private mixed $task;
    
    public function __construct(mixed $task = null, ?OwnerScope $parent = null)
    {
        parent::__construct($parent);
        $this->task = $task;
    }
    
    public function spawn(callable $fn): ChildFiberOwner
    {
        return (new ChildFiberOwner($this))->spawn(fn($ctx) => $fn($ctx));
    }
}
