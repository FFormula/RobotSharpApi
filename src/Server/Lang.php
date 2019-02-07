<?php

namespace FFormula\RobotSharpApi\Server;

use FFormula\RobotSharpApi\System\Base;

class Lang extends Base
{
    public function getLangList(array $get) : string
    {
        $lang = new \FFormula\RobotSharpApi\Model\Lang();
        $list = $lang->selectAll();
        return $this->answer($list);
    }

}