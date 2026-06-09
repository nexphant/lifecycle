<?php

namespace Nexph\Lifecycle;

interface Owner
{
    public function own(mixed $resource): mixed;
    public function child(mixed $child = null): OwnerScope;
    public function cancel(): void;
    public function isCancelled(): bool;
    public function close(): void;
    public function isClosed(): bool;
}
