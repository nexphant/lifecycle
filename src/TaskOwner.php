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
        $this->assertOpen();
        $childCtx = new ChildFiberOwner($this);
        $this->child($childCtx);
        $fiber = new \Fiber(function () use ($fn, $childCtx): void {
            try {
                $fn($childCtx);
            } finally {
                $childCtx->close();
            }
        });
        $fiber->start();
        return $childCtx;
    }
}
