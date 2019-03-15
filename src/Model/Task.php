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
                   caption, video, description
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
            SELECT task.id, authorId, caption, video, sector, 
                   MAX(p1.points) as points,
                   COUNT(DISTINCT p2.userId) as users
              FROM task
         LEFT JOIN taskDict 
                ON task.id = taskDict.taskId
               AND dictId = ?
         LEFT JOIN program p1 
                ON task.id = p1.taskId
               AND p1.userId = ?
         LEFT JOIN program p2
                ON task.id = p2.taskId
               AND p2.points = 100
             WHERE task.status = "show"
          GROUP BY task.id
          ORDER BY task.step', [ $dictId, $userId ]);
    }

}