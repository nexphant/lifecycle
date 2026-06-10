<?php

namespace Nexph\Lifecycle;

class RequestOwner extends AbstractOwner
{
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
