<?php

namespace FFormula\RobotSharpApi\Model;

class Partner extends Record
{
    public function selectByName($name): Partner
    {
        $this->row = $this->db->select1Row('
            SELECT id, name, apikey, status, info
              FROM partner
             WHERE name = ?', [ $name ]
        );
        return $this;
    }
}