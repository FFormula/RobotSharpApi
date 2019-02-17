<?php

namespace FFormula\RobotSharpApi\Model;

/**
 * Class Partner - Работа с таблицей Partner
 * @package FFormula\RobotSharpApi\Model
 */
class Partner extends Record
{
    /**
     * @param string $name
     * @return Partner
     * @throws \Exception
     */
    public function selectByName(string $name): Partner
    {
        $this->row = $this->db->select1Row('
            SELECT id, name, apikey, status, info
              FROM partner
             WHERE name = ?', [ $name ]
        );
        return $this;
    }
}