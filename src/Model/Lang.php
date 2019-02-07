<?php
/**
 * Created by PhpStorm.
 * User: fform
 * Date: 2/6/2019
 * Time: 8:21 AM
 */

namespace FFormula\RobotSharpApi\Model;

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