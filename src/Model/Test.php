<?php

namespace FFormula\RobotSharpApi\Model;

class Test extends Record
{
    public function getDemoTest($taskId) : Test
    {
        $this->row = $this->db->select1Row('
            SELECT taskId, testNr, fileIn, fileOut
              FROM test     
             WHERE taskID = ?
               AND test.testNr = 0', [$taskId]);
        $this->row['fileInRows']  = $this->getRowsCount($this->row['fileIn']);
        $this->row['fileOutRows'] = $this->getRowsCount($this->row['fileOut']);
        return $this;
    }

    function getRowsCount($text)
    {
        return substr_count($text, "\n");
    }

}