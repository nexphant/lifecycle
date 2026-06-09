<?php

namespace Nexph\Lifecycle;

interface Owner
{
    public function own(mixed $resource): void;
    public function child(mixed $child): void;
    public function close(): void;
    public function isClosed(): bool;
}
