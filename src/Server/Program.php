<?php

namespace FFormula\RobotSharpApi\Server;

use FFormula\RobotSharpApi\System\Base;

class Program extends Base
{
    public function getProgram(array $get) : string
    {
        if (!$get['taskId'])
            return $this->error('taskId not specified');

        if (!$get['langId'])
            return $this->error('langId not specified');

        if (!$this->user->row['id'])
            return $this->error('No user id');

        $program = (new \FFormula\RobotSharpApi\Model\Program())->selectByKeys(
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

}