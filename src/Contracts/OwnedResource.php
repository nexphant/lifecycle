<?php

namespace Nexphant\Lifecycle\Contracts;

interface OwnedResource
{
    public function close(): void;
}
