<?php

namespace FFormula\RobotSharp\ApiClient;

use FFormula\RobotSharp\ApiSystem\Base;

class Prog extends Base
{
    public function getProg(array $get) : string
    {
        if (!$get['taskId'])
            return $this->error('TaskId not specified');

        if (!$get['langId'])
            return $this->error('LangId not specified');

        if (!$this->user->row['id'])
            return $this->error('No user id');

        $prog = (new \FFormula\RobotSharp\Model\Prog())->selectByKeys(
            $this->user->row['id'],
            $get['taskId'],
            $get['langId']);

        return $this->answer($prog->row);
    }

}