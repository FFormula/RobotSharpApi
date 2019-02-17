<?php

namespace FFormula\RobotSharpApi\Model;

/**
 * Class Test - Работа с таблицей Test
 * @package FFormula\RobotSharpApi\Model
 */
class Test extends Record
{
    /**
     * @param string $taskId
     * @return Test
     * @throws \Exception
     */
    public function getDemoTest(string $taskId) : Test
    {
        $this->row = $this->db->select1Row('
            SELECT taskId, testNr, fileIn, fileOut
              FROM test     
             WHERE taskId = ?
               AND test.testNr = 0', [$taskId]);
        $this->row['fileInRows']  = $this->getRowsCount($this->row['fileIn']);
        $this->row['fileOutRows'] = $this->getRowsCount($this->row['fileOut']);
        return $this;
    }

    /**
     * @param string $taskId
     * @return array
     * @throws \Exception
     */
    public function getAllTests(string $taskId) : array
    {
        return $this->db->selectRows('
            SELECT taskId, testNr, fileIn, fileOut
              FROM test     
             WHERE taskId = ?', [$taskId]);
    }

    private function getRowsCount(string $text) : int
    {
        return 1 + substr_count(trim($text), "\n");
    }

}