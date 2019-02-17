<?php

namespace FFormula\RobotSharpApi\Api;

use FFormula\RobotSharpApi\Model\Lang;

/**
 * Class ApiLang - Работа со списком языков программирования
 * @package FFormula\RobotSharpApi\Api
 */
class ApiLang extends Api
{
    /**
     * Получить список всех доступных языков программирования
     * @param array $get - не используется
     * @return string - список языков с шаблонами исходных кодов программ
     */
    public function getLangList(array $get) : array
    {
        $lang = new Lang();
        return $lang->selectAll();
    }
}