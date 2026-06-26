<?php

namespace Nexphant\Lifecycle;

class Lifecycle
{
    public static function worker(?OwnerScope $parent = null): WorkerOwner
    {
        return new WorkerOwner($parent);
    }

    public static function request(?OwnerScope $parent = null): RequestOwner
    {
        return new RequestOwner($parent);
    }

    public static function job(mixed $job = null, ?OwnerScope $parent = null): JobOwner
    {
        return new JobOwner($job, $parent);
    }

    public static function task(mixed $task = null, ?OwnerScope $parent = null): TaskOwner
    {
        return new TaskOwner($task, $parent);
    }

    public static function message(?OwnerScope $parent = null): MessageOwner
    {
        return new MessageOwner($parent);
    }

    public static function childFiber(?OwnerScope $parent = null): ChildFiberOwner
    {
        return new ChildFiberOwner($parent);
    }
}
