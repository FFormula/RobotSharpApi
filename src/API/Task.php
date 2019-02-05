<?php

namespace FFormula\RobotSharp\API;

class Task extends Api
{
    public function getTask(array $get) : string
    {
        if (!$get['taskId'])
            return $this->error('taskId not specified');

        $task = (new \FFormula\RobotSharp\Model\Task())->selectById($get['taskId']);

        if (!$task->row['id'])
            return $this->error('task not found');

        return $this->answer($task->row);
    }

    public function getTaskList(array $get) : string
    {
        $task = new \FFormula\RobotSharp\Model\Task();
        $list = $task->getList('ru');
        return $this->answer($list);
    }

}