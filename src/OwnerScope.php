<?php

namespace nexphant\Lifecycle;

use nexphant\Lifecycle\Contracts\OwnedResource;

class OwnerScope
{
    protected array $resources = [];
    protected array $children = [];
    protected bool $closed = false;
    protected bool $cancelled = false;
    protected ?OwnerScope $parent = null;
    protected float $createdAt;
    protected float $closedAt = 0.0;

    public function __construct(?OwnerScope $parent = null)
    {
        $this->parent = $parent;
        $this->cancelled = $parent?->isCancelled() ?? false;
        $this->createdAt = microtime(true);
    }
    
    public function metrics(): array
    {
        return [
            'resource_count' => count($this->resources),
            'child_count' => count($this->children),
            'duration_ms' => $this->closedAt > 0 ? ($this->closedAt - $this->createdAt) * 1000 : 0,
            'cancelled' => $this->cancelled,
        ];
    }

    public function own(mixed $resource): mixed
    {
        $this->assertOpen();
        $this->resources[] = $resource;
        return $resource;
    }

    public function ownResource(OwnedResource $resource): OwnedResource
    {
        $this->assertOpen();
        $this->resources[] = $resource;
        return $resource;
    }

    public function child(mixed $child = null): OwnerScope
    {
        $this->assertOpen();
        if ($child === null) {
            $child = new OwnerScope($this);
        } elseif ($child instanceof OwnerScope && $child->parent === null) {
            $child->parent = $this;
            if ($this->cancelled) {
                $child->cancel();
            }
        }
        $this->children[] = $child;
        return $child instanceof OwnerScope ? $child : $this;
    }

    public function cancel(): void
    {
        if ($this->cancelled) {
            return;
        }
        $this->cancelled = true;
        foreach ($this->children as $child) {
            if ($child instanceof OwnerScope) {
                $child->cancel();
            } elseif ($child instanceof Owner) {
                $child->cancel();
            }
        }
    }

    public function isCancelled(): bool
    {
        return $this->cancelled || ($this->parent?->isCancelled() ?? false);
    }

    public function close(): void
    {
        if ($this->closed) {
            return;
        }

        while ($child = array_pop($this->children)) {
            if ($child instanceof OwnerScope || $child instanceof Owner) {
                try {
                    $child->close();
                } catch (\Throwable) {
                }
            }
        }

        while ($resource = array_pop($this->resources)) {
            try {
                if ($resource instanceof OwnedResource) {
                    $resource->close();
                } elseif (is_resource($resource)) {
                    @fclose($resource);
                }
            } catch (\Throwable) {
            }
        }

        $this->closed = true;
        $this->closedAt = microtime(true);
        $this->parent = null;
    }

    public function detach(): void
    {
        $this->parent = null;
    }

    public function isClosed(): bool
    {
        return $this->closed;
    }

    protected function assertOpen(): void
    {
        if ($this->closed) {
            throw new \RuntimeException('Owner closed');
        }
    }
}
