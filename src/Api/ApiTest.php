<?php

namespace FFormula\RobotSharpApi\Api;

use FFormula\RobotSharpApi\Model\Test;

/**
 * Class ApiTest - Получение тестов к задачам
 * @package FFormula\RobotSharpApi\Api
 */
class ApiTest extends Api
{
    /**
     * Получение нулевого демо-теста к задаче
     * @param array $get
     *          taskId - номер задачи
     * @return string
     *          taskId,
     *          testNr,
     *          fileIn,
     *          fileOut
     * @throws \Exception - в случае любой ошибки
     */
    public function getDemoTest(array $get) : array
    {
        if (!$get['taskId'])
            throw new \Exception('taskId not specified');

        $test = (new Test())->getDemoTest($get['taskId']);

        if (!$test->row['taskId'])
            throw new \Exception('Demo test for this task ' . $get['taskId'] . ' not found');

        return $test->row;
    }

    /**
     * @param array $get
     * @return array
     * @throws \Exception
     */
    public function getAllTests(array $get) : array
    {
        if (!$get['taskId'])
            throw new \Exception('taskId not specified');

        $tests = (new Test())->getAllTests($get['taskId']);

        return $tests;
    }
}