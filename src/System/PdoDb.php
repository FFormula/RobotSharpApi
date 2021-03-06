<?php

namespace FFormula\RobotSharpApi\System;

/**
 * Class PdoDb - Вариант реализации DbInterface на основе PDO
 * @package FFormula\RobotSharpApi\System
 */
class PdoDb implements DbInterface
{
    /** @var \PDO */
    var $pdo;

    /** @var \PDOStatement */
    var $sth;

    function __construct(array $config)
    {
        $this->pdo = new \PDO($config['dsn'],
                              $config['user'],
                              $config['pass']);
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    public function execute(string $query, array $param = []): bool
    {
        Log::get()->debug('Query: ' . $query);
        if (count($param) > 0)
            Log::get()->debug('Param: ' . json_encode($param));
        $this->sth = $this->pdo->prepare($query);
        return $this->sth->execute($param);
    }

    public function selectValue(string $query, array $param = []): string
    {
        $row = $this->select1Row($query, $param);
        foreach ($row as $value)
            return $value;
        return '';
    }

    public function select1Row(string $query, array $param = []): array
    {
        $rows = $this->selectRows($query, $param);
        if (count($rows) == 0) return [];
        if (!is_array($rows[0])) return [];
        Log::get()->debug('Get1Row: ' . json_encode($rows[0]));
        return $rows[0];
    }

    public function selectRows(string $query, array $param = []): array
    {
        $this->execute($query, $param);
        $arr = $this->sth->fetchAll(\PDO::FETCH_ASSOC);
        if ($arr == null) return [];
        Log::get()->debug('GetRows: #' . count($arr));
        return $arr;
    }

    function getLastInsertId(): string
    {
        $id = $this->pdo->lastInsertId();
        Log::get()->debug('GetLastId: ' . $id);
        return $id;
    }
}