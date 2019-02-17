<?php

namespace FFormula\RobotSharpApi\System;

/**
 * Interface DbInterface - Абстракция для возможности подлючения других движков баз данных
 * @package FFormula\RobotSharpApi\System
 */
interface DbInterface
{
    /**
     * Выполнить запрос вида Update / Insert / Delete
     * @param string $query - Запрос с параметрами
     * @param array $param - список параметров
     * @return bool - True - запрос выполнен,
     *                False - ошибка при выполнении (в этом случае должно выкидываться исключение)
     * @throws \Exception - при любой ошибке должно кидаться исключение
     */
    function execute(string $query, array $param = []) : bool;

    /**
     * Получение последнего добавленного значения счётчика для главного ключа
     * @return string - значение счётчика
     * @throws \Exception - при любой ошибке должно кидаться исключение
     */
    function getLastInsertId() : string;

    /**
     * Выбрать скалярное значение по запрос: берётся первое значение в первой строке
     * @param string $query - Запрос с параметрами
     * @param array $param - список параметров
     * @return string - скалярное значение
     * @throws \Exception - при любой ошибке должно кидаться исключение
     */
    function selectValue(string $query, array $param = []): string;

    /**
     * Выбрать одну строчку по запросу и вернуть её в виде массива
     * @param string $query - Запрос с параметрами
     * @param array $param - список параметров
     * @return array - ассоциативный массив со значениями полей, сразу
     * @throws \Exception - при любой ошибке должно кидаться исключение
     */
    function select1Row(string $query, array $param = []) : array;

    /**
     * Выбрать все строчки по запросу и вернуть в виде массива строк
     * @param string $query - Запрос с параметрами
     * @param array $param - список параметров
     * @return array - нумерованный массив из ассоциативных массивов
     * @throws \Exception - при любой ошибке должно кидаться исключение
     */
    function selectRows(string $query, array $param = []) : array;
}