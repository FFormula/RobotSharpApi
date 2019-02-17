<?php
namespace FFormula\RobotSharpApi\System;

use \Psr\Log\LoggerInterface;
use \Monolog\Logger;
use \Monolog\Handler\StreamHandler;

/**
 * Class Log - Статическая обёртка для создания и получения логгера
 * @package FFormula\RobotSharpWeb\System
 */
class Log
{
    /** @var LoggerInterface - экземпляр стандартного логгера */
    private static $log = null;

    /**
     * создание статического логгера
     * @param $name - имя для отображения в логе
     * @param $filename - имя файла для ведения логов
     */
    public static function set(string $name, string $filename) : void
    {
        try
        {
            $handler = new StreamHandler($filename, Logger::DEBUG);
            self::$log = new Logger($name, [$handler]);
        } catch (\Exception $ex) {
            die ("Logger not set: " . $ex->getMessage());
        }
    }

    /**
     * получение статического логгера по стандартному интерфейсу
     * @return LoggerInterface - возвращает экземпляр стандартного логгера
     */
    public static function get() : LoggerInterface
    {
        if (self::$log == null)
            die ("Logger not set");
        return self::$log;
    }
}