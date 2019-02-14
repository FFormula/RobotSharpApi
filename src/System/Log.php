<?php
namespace FFormula\RobotSharpApi\System;

use \Psr\Log\LoggerInterface;

class Log
{
    /** @var LoggerInterface */
    private static $log = null;

    public static function set(LoggerInterface $log)
    {
        self::$log = $log;
    }

    public static function get() : LoggerInterface
    {
        if (self::$log == null)
            die ("Logger not set");
        return self::$log;
    }
}