<?php

namespace Nexph\Lifecycle;

abstract class AbstractOwner implements Owner
{
    protected array $resources = [];
    protected array $children = [];
    protected bool $closed = false;

    public function own(mixed $resource): void
    {
        $this->assertOpen();
        $this->resources[] = $resource;
    }

    public function child(mixed $child): void
    {
        $this->assertOpen();
        $this->children[] = $child;
    }

    public function close(): void
    {
        if ($this->closed) {
            return;
        }
        $this->closed = true;

        while ($child = array_pop($this->children)) {
            if (method_exists($child, 'cancel')) {
                $child->cancel();
            } elseif (method_exists($child, 'close')) {
                $child->close();
            }
        }

        while ($resource = array_pop($this->resources)) {
            if (method_exists($resource, 'close')) {
                $resource->close();
            } elseif (is_resource($resource)) {
                @fclose($resource);
            }
        }
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
