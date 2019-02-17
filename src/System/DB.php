<?php
namespace FFormula\RobotSharpApi\System;

/**
 * Class DB - Статическая обёртка для работы с базой данных
 * @package FFormula\RobotSharpApi\System
 */
class DB
{
    /** @var DbInterface */
    private static $db = null;

    public static function set(DbInterface $db) : void
    {
        self::$db = $db;
    }

    public static function get() : DbInterface
    {
        if (self::$db == null)
            die('Db not set');
        return self::$db;
    }
}