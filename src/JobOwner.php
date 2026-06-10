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
