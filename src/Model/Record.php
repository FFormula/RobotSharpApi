<?php

namespace FFormula\RobotSharp\Model;

use FFormula\RobotSharp\Service\DB;

class Record
{
    /** @var DB */
    var $db;

    /** @var array */
    var $row;

    public function __construct()
    {
        global $db;
        $this->db = $db;
    }

}