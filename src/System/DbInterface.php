<?php

namespace FFormula\RobotSharpApi\System;

interface DbInterface
{
    function execute(string $query, array $param = []): bool;
    function getLastInsertId(): string;
    function selectValue(string $query, array $param = []): string;
    function select1Row(string $query, array $param = []) : array;
    function selectRows(string $query, array $param = []) : array;
}