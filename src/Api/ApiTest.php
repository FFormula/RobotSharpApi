<?php

namespace FFormula\RobotSharpApi\Api;

use FFormula\RobotSharpApi\Model\Test;

/**
 * Class ApiTest - Получение тестов к задачам
 * @package FFormula\RobotSharpApi\Api
 */
class ApiTest extends Base
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
     */
    public function getDemoTest(array $get) : string
    {
        if (!$get['taskId'])
            return $this->error('taskId not specified');

        $test = (new Test())->getDemoTest($get['taskId']);

        if (!$test->row['taskId'])
            return $this->error('Demo test for this task ' . $get['taskId'] . ' not found');

        return $this->answer($test->row);
    }
}