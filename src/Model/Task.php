<?php

namespace FFormula\RobotSharpApi\Model;

/**
 * Class Task - Работа с таблицей Task
 * @package FFormula\RobotSharpApi\Model
 */
class Task extends Record
{
    /**
     * @param string $taskId
     * @param string $dictId
     * @return Task
     * @throws \Exception
     */
    public function selectById(string $taskId, string $dictId) : Task
    {
        $this->row = $this->db->select1Row('
            SELECT id, authorId, name, sectorId, sector, step,
                   caption, description
              FROM task
         LEFT JOIN taskDict 
                ON task.id = taskDict.taskId 
               AND taskDict.dictId = :dictId
             WHERE id = :taskId',
            [
                'taskId' => $taskId,
                'dictId' => $dictId
            ]);
        return $this;
    }

    /**
     * @param string $dictId
     * @return array
     * @throws \Exception
     */
    public function getList(string $dictId) : array
    {
        return $this->db->selectRows('
            SELECT task.id, authorId, caption, sector
              FROM task
              JOIN taskDict 
                ON task.id = taskDict.taskId
               AND dictId = ?
          ORDER BY task.step', [ $dictId ]);
    }

}