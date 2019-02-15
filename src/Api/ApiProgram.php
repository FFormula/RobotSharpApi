<?php

namespace FFormula\RobotSharpApi\Api;

use \FFormula\RobotSharpApi\Model\Program;

class ApiProgram extends Base
{
    public function getProgram(array $get) : string
    {
        if (!$get['taskId'])
            return $this->error('taskId not specified');

        if (!$get['langId'])
            return $this->error('langId not specified');

        if (!$this->user->row['id'])
            return $this->error('No user id');

        $program = (new Program())->selectByKeys(
            $this->user->row['id'],
            $get['taskId'],
            $get['langId']);

        if ($program->row['program'])
            return $this->answer($program->row);

        $program->setDefaults(
            $this->user->row['id'],
                $get['taskId'],
                $get['langId']);

        return $this->answer($program->row);
    }

    public function runProgram(array $get) : string
    {
        if (!$get['taskId'])
            return $this->error('taskId not specified');

        if (!$get['langId'])
            return $this->error('langId not specified');

        if (!$this->user->row['id'])
            return $this->error('No user id');

        if (!$get['source'])
            return $this->error('Source not specified');

        $program = (new Program())->insert(
            $this->user->row['id'],
            $get['taskId'],
            $get['langId'],
            $get['source']);
        return $this->answer([]);
    }

}