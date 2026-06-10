<?php

namespace Nexph\Lifecycle;

class ChildFiberOwner extends AbstractOwner
{
    public function __construct(?OwnerScope $parent = null)
    {
        parent::__construct($parent);
        if ($parent && $parent->isCancelled()) {
            $this->cancel();
        }
    }

    public function own(mixed $resource): mixed
    {
        if ($this->parent?->isClosed()) {
            if (RuntimeDiscipline::enabled()) {
                throw new \RuntimeException('Parent owner closed');
            }
        }
        return parent::own($resource);
    }
    
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
