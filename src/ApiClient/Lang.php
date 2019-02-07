<?php

namespace FFormula\RobotSharpApi\ApiClient;

use FFormula\RobotSharpApi\ApiSystem\Base;

class Lang extends Base
{
    public function getLangList(array $get) : string
    {
        $lang = new \FFormula\RobotSharp\Model\Lang();
        $list = $lang->selectAll();
        return $this->answer($list);
    }

}