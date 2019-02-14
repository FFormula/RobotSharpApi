<?php

namespace FFormula\RobotSharpApi\Api;

use FFormula\RobotSharpApi\Model\Lang;

class ApiLang extends Base
{
    public function getLangList(array $get) : string
    {
        $lang = new Lang();
        $list = $lang->selectAll();
        return $this->answer($list);
    }

}