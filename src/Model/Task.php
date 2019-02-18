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
    public function getList(string $dictId, string $userId) : array
    {
        return $this->db->selectRows('
            SELECT task.id, authorId, caption, sector, MAX(points) as points
              FROM task
         LEFT JOIN taskDict 
                ON task.id = taskDict.taskId
               AND dictId = ?
         LEFT JOIN program 
                ON task.id = program.taskId
               AND program.userId = ?
          GROUP BY task.id
          ORDER BY task.step', [ $dictId, $userId ]);
    }

}