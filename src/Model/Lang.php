<?php

namespace FFormula\RobotSharpApi\Model;

/**
 * Class Lang - Работа с таблицей Lang
 * @package FFormula\RobotSharpApi\Model
 */
class Lang extends Record
{
    public function selectByKey(string $langId) : Lang
    {
        $this->row = $this->db->select1Row(
            'SELECT id, lang, source
               FROM lang
              WHERE id = ?', [ $langId ]);
        return $this;
    }

    public function selectAll() : array
    {
        return $this->db->selectRows(
            'SELECT id, lang, source
               FROM lang');
    }
}