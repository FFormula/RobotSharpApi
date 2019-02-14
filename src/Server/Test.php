<?php

namespace FFormula\RobotSharpApi\Server;

use FFormula\RobotSharpApi\System\Base;

class Test extends Base
{
    public function getDemoTest(array $get) : string
    {
        if (!$get['taskId'])
            return $this->error('taskId not specified');

        $test = (new \FFormula\RobotSharpApi\Model\Test())->getDemoTest($get['taskId']);

        if (!$test->row['taskId'])
            return $this->error('Demo test for this task ' . $get['taskId'] . ' not found');

        return $this->answer($test->row);
    }

    public function getTaskList(array $get) : string
    {
        $task = new \FFormula\RobotSharpApi\Model\Task();
        $list = $task->getList('ru');
        return $this->answer($list);
    }

}