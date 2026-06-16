<?php

namespace Nexphant\Lifecycle;

class Lifecycle
{
    public static function worker(): WorkerOwner
    {
        return new WorkerOwner();
    }

    public static function request(): RequestOwner
    {
        return new RequestOwner();
    }

    public static function job($job): JobOwner
    {
        return new JobOwner();
    }

    public static function task($task): TaskOwner
    {
        return new TaskOwner();
    }

    public static function message(): MessageOwner
    {
        return new MessageOwner();
    }

    public static function childFiber(?OwnerScope $parent = null): ChildFiberOwner
    {
        return new ChildFiberOwner($parent);
    }
}
