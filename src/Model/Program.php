<?php

namespace FFormula\RobotSharpApi\Model;

class Program extends Record
{
    public function selectByKeys($userId, $taskId, $langId) : Program
    {
        $this->row = $this->db->select1Row('
            SELECT runkey, points, runs, source, answer
              FROM program
             WHERE userId = ?
               AND taskId = ?
               AND langId = ?', [ $userId, $taskId, $langId ]);
        return $this;
    }

    public function setDefaults($userId, $taskId, $langId) : Program
    {
        $lang = (new Lang())->selectByKey($langId);
        $this->row = [
            'userId' => $userId,
            'taskId' => $taskId,
            'langId' => $langId,
            'runkey' => '',
            'points' => 0,
            'runs' => 0,
            'source' => $lang->row['source'],
            'answer' => ''
        ];
        return $this;
    }

    public function insert($userId, $taskId, $langId, $source) : bool
    {
        return $this->db->execute('
            INSERT INTO program
               SET userId = :userId,
                   taskId = :taskId,
                   langId = :langId,
                   runkey = :runkey,
                   points = 0,
                   runs = runs + 1,
                   source = :source,
                   answer = ""',
            [
                'userId' => $userId,
                'taskId' => $taskId,
                'langId' => $langId,
                'runkey' => '',
                'points' => 0,
                'runs' => 0,
                'source' => $source,
                'answer' => ''
            ]);
    }


}