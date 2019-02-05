<?php

namespace FFormula\RobotSharp\Model;

class Prog extends Record
{
    public function selectByKeys($userId, $taskId, $langId) : Prog
    {
        $this->row = $this->db->select1Row('
            SELECT runkey, points, runs, source, answer
              FROM prog
             WHERE userId = ?
               AND taskId = ?
               AND langId = ?', [ $userId, $taskId, $langId ]);
        return $this;
    }

}