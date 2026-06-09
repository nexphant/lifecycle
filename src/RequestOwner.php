<?php

namespace Nexph\Lifecycle;

class RequestOwner extends AbstractOwner
{
    public function spawn(callable $fn): ChildFiberOwner
    {
        $childCtx = new ChildFiberOwner($this);
        $this->child($childCtx);
        
        $fiber = new \Fiber(function() use ($fn, $childCtx) {
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
