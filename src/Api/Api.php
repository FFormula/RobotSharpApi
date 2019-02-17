<?php

namespace FFormula\RobotSharpApi\Api;

use FFormula\RobotSharpApi\Model\User;
use FFormula\RobotSharpApi\System\Robot;

/**
 * Class Base Базовый класс для всех Api-классов
 * Содержит методы для формирования результата и ошибок
 * @package FFormula\RobotSharpApi\Api
 */
abstract class Api
{
    /** @var User - данные подключившегося пользователя */
    protected $user;

    /** @var Robot - для запуска робота и получения результатов тестирования  */
    public $robot;
}

