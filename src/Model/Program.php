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

    public function setDefaults($userId, $taskId, $langId, $source = '') : Program
    {
        if ($source == '')
            $source = (new Lang())->selectByKey($langId)->row['source'];

        $this->row = [
            'userId' => $userId,
            'taskId' => $taskId,
            'langId' => $langId,
            'runkey' => '',
            'points' => 0,
            'runs' => 0,
            'source' => $source,
            'answer' => ''
        ];
        return $this;
    }

    public function insert($userId, $taskId, $langId, $source) : bool
    {
        $this->setDefaults($userId, $taskId, $langId, $source);
        return $this->db->execute('
       INSERT INTO program
               SET userId = :userId,
                   taskId = :taskId,
                   langId = :langId,
                   runkey = :runkey,
                   points = :points,
                   runs = :runs,
                   source = :source,
                   answer = :answer', $this->row);
    }

    public function update($userId, $taskId, $langId, $source) : bool
    {
        $this->setDefaults($userId, $taskId, $langId, $source);
        return $this->db->execute('
            UPDATE program
               SET runkey = :runkey,
                   points = :points,
                   runs = :runs,
                   source = :source,
                   answer = :answer
             WHERE userId = :userId
               AND taskId = :taskId
               AND langId = :langId', $this->row);
    }


}