<?php

namespace FFormula\RobotSharpApi\Api;

use FFormula\RobotSharpApi\Model\Lang;

/**
 * Class ApiLang - Работа со списком языков программирования
 * @package FFormula\RobotSharpApi\Api
 */
class ApiLang extends Base
{
    /**
     * Получить список всех доступных языков программирования
     * @param array $get - не используется
     * @return string - список языков с шаблонами исходных кодов программ
     */
    public function getLangList(array $get) : string
    {
        if ($get == []) return ''; // не знаю, как избавиться от варнинга, что параметр не используется :(
        $lang = new Lang();
        $list = $lang->selectAll();
        return $this->answer($list);
    }
}