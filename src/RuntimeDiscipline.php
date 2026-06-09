<?php

namespace Nexph\Lifecycle;

class RuntimeDiscipline
{
    public static bool $enabled = true;
    public static bool $objectTracking = false;
    public static bool $resourceTrace = false;
    public static bool $leakDetection = false;

    public static function enable(): void
    {
        self::$enabled = true;
    }

    public static function disable(): void
    {
        self::$enabled = false;
    }

    public static function enableTracking(): void
    {
        self::$objectTracking = true;
        self::$resourceTrace = true;
        self::$leakDetection = true;
    }

    public static function disableTracking(): void
    {
        self::$objectTracking = false;
        self::$resourceTrace = false;
        self::$leakDetection = false;
    }
}
