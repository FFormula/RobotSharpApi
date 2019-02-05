<?php
/**
 * Created by PhpStorm.
 * User: 308
 * Date: 2/5/2019
 * Time: 9:46 AM
 */

namespace FFormula\RobotSharp\Model;


class Task extends Record
{
    public function selectById($taskId) : Task
    {
        $this->row = $this->db->select1Row('
            SELECT id, authorId, task, sectorId, sector, step
              FROM task
             WHERE id = ?', [$taskId]);
        return $this;
    }

    public function getList($dictId) : array
    {
        return $this->db->selectRows('
            SELECT task.id taskId, authorId, caption, sector
              FROM task
              JOIN taskDict 
                ON task.id = taskDict.taskId
               AND dictId = ?
          ORDER BY task.step', [ $dictId ]);
    }

}