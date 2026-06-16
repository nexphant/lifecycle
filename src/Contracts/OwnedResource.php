<?php

namespace nexphant\Lifecycle\Contracts;

interface OwnedResource
{
    public function close(): void;
}
