<?php
namespace FFormula\RobotSharpApi\Model;

use FFormula\RobotSharpApi\System\DB;
use FFormula\RobotSharpApi\System\DbInterface;

/**
 * Class Record - Базовый класс для работы с таблицами
 * @package FFormula\RobotSharpApi\Model
 */
abstract class Record
{
    /** @var DbInterface */
    var $db;
    /** @var array */
    var $row;

    public function __construct()
    {
        $this->db = DB::get();
    }
}