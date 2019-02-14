<?php

namespace FFormula\RobotSharpApi\Api;

use FFormula\RobotSharpApi\Model\Test;

class ApiTest extends Base
{
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