<?php

namespace FFormula\RobotSharpApi\Api;

use FFormula\RobotSharpApi\Model\Login;
use FFormula\RobotSharpApi\Model\User;

/**
 * Class Base Базовый класс для всех Api-классов
 * Содержит методы для формирования результата и ошибок
 * @package FFormula\RobotSharpApi\Api
 */
abstract class Api
{
    /** @var User - данные подключившегося пользователя */
    protected $user;

    /**
     * Получение записи о пользователе по token-у с проверкой его наличия
     * @param string $token
     * @throws \Exception
     */
    public function setUserByToken(string $token) : void
    {
        $login = (new Login())->selectByToken($token);
        if (!$login->row['userId'])
            throw new \Exception('Token not found or expired');

        $this->user = (new User())->selectById($login->row['userId']);
        if (!$this->user->row['id'])
            throw new \Exception('User not found');
    }

}

