<?php

namespace FFormula\RobotSharpApi\Model;

/**
 * Class Lang - Работа с таблицей Lang
 * @package FFormula\RobotSharpApi\Model
 */
class Lang extends Record
{
    /**
     * @param string $langId
     * @return Lang
     * @throws \Exception
     */
    public function selectByKey(string $langId) : Lang
    {
        $this->row = $this->db->select1Row(
            'SELECT id, lang, source
               FROM lang
              WHERE id = ?', [ $langId ]);
        return $this;
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function selectAll() : array
    {
        return $this->db->selectRows(
            'SELECT id, lang, source
               FROM lang');
    }
}