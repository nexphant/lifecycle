<?php

namespace Nexph\Lifecycle;

class RuntimeDiscipline
{
    public static bool $enabled = true;
    public static bool $objectTracking = false;
    public static bool $resourceTrace = false;
    public static bool $leakDetection = false;

    public static function configure(array $config): void
    {
        $enabled = $config['runtime_discipline'] ?? $config['runtime_safety'] ?? self::$enabled;
        self::$enabled = (bool) $enabled;
        self::$objectTracking = (bool) ($config['object_tracking'] ?? self::$objectTracking);
        self::$resourceTrace = (bool) ($config['resource_trace'] ?? self::$resourceTrace);
        self::$leakDetection = (bool) ($config['leak_detection'] ?? self::$leakDetection);
    }
    
    public static function enabled(): bool
    {
        return self::$enabled;
    }

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
    
    public static function assertDetachedOwner(): void
    {
        if (!self::$enabled) {
            return;
        }
        throw new \RuntimeException('Detached fiber requires explicit owner');
    }
    
    public static function assertOwnerOpen(OwnerScope $owner): void
    {
        if (!self::$enabled) {
            return;
        }
        if ($owner->isClosed()) {
            throw new \RuntimeException('Owner closed');
        }
    }
}
