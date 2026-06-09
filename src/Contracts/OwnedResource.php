<?php

namespace Nexph\Lifecycle\Contracts;

interface OwnedResource
{
    public function close(): void;
}
