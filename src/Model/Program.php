<?php

namespace FFormula\RobotSharpApi\Model;

/**
 * Работа с таблицей Program - база всех программ
 * с результатом последнего запуска на проверку
 * Class Program
 * @package FFormula\RobotSharpApi\Model
 */
class Program extends Record
{
    /**
     * @param $userId - номер пользователя
     * @param $taskId - номер задачи
     * @param $langId - код языка программирования
     * @return Program - этот экземпляр с данными в row
     */
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

        if (isset($this->row['runs']))
            $runs = $this->row['runs'] + 1;
        else
            $runs = 0;

        $this->row = [
            'userId' => $userId,
            'taskId' => $taskId,
            'langId' => $langId,
            'runkey' => '',
            'points' => 0,
            'runs'   => $runs,
            'source' => $source,
            'answer' => ''
        ];
        return $this;
    }

    public function insert($userId, $taskId, $langId, $source) : bool
    {
        $this->setDefaults($userId, $taskId, $langId, $source);
        $this->generateRunkey();
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
        $this->generateRunkey();
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

    private function generateRunkey()
    {
        $this->row['runkey'] =
            date('ymd.His', time()) .
            '.' . $this->row['userId'] .
            '.' . $this->row['taskId'] .
            '.' . $this->row['langId'] ;
    }
}