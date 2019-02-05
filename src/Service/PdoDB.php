<?php

namespace FFormula\RobotSharp\Service;

class PdoDB implements DB
{
    /** @var \PDO */
    var $pdo;

    /** @var \PDOStatement */
    var $sth;

    function __construct($pdo)
    {
        $this->pdo = $pdo;
        $this->pdo->setAttribute(
            \PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    public function execute(string $query, array $param = []): bool
    {
        $this->sth = $this->pdo->prepare($query);
        return $this->sth->execute($param);
    }

    public function selectValue(string $query, array $param = []): string
    {
        $row = $this->select1Row($query, $param);
        if (count($row) == 0) return '';
        return $row[0];
    }

    public function select1Row(string $query, array $param = []): array
    {
        $rows = $this->selectRows($query, $param);
        if (count($rows) == 0) return [];
        if (!is_array($rows[0])) return [];
        return $rows[0];
    }

    public function selectRows(string $query, array $param = []): array
    {
        $this->execute($query, $param);
        $arr = $this->sth->fetchAll(\PDO::FETCH_ASSOC);
        if ($arr == null) return [];
        return $arr;
    }

    function getLastInsertId(): string
    {
        return $this->pdo->lastInsertId();
    }
}