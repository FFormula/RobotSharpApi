<?php

namespace FFormula\RobotSharpApi\Api;

use FFormula\RobotSharpApi\Model\Login;
use FFormula\RobotSharpApi\Model\Partner;
use FFormula\RobotSharpApi\Model\User;

/**
 * Class ApiLogin - Регистрация и подключение пользователей
 * @package FFormula\RobotSharpApi\Api
 */
class ApiLogin extends Base
{
    /**
     * Получение token для нового/старого поользователя,
     * который подключился в систему от указанного партнёра
     * @param $get array - данные о пользователе с подписью партнёра
     *      partner - код партнёра
     *      email - электропочта пользователя
     *      time - время в секундах момента генерации ссылки, действует 24 часа
     *      sign - подпись партнёра, формируется по правилу:
     *             md5($partnerName/$apikey/$time/$email)
     *      name - имя нового пользователя для его регистрации
     * @return string
     *      token - сгенерированный token для подключения
     *      userId - номер нового/подключённого пользователя
     */
    public function getUserToken(array $get) : string
    {
        if (!$get['partner'])
            return $this->error('partner not specified');

        if (!$get['email'])
            return $this->error('email not specified');

        if (!$get['time'])
            return $this->error('time not specified');

        if (!$get['sign'])
            return $this->error('sign not specified');

        $partner = (new Partner())->selectByName($get['partner']);

        if (!$partner->row['id'])
            return $this->error('partner not found');

        if ($partner->row['status'] != '1')
            return $this->error('partner disabled');

        $login = new Login();

        if ($login->isTimeExpired($get['time']))
            return $this->error('link time expired');

        $signHost = $login->getSign($partner->row['name'], $partner->row['apikey'],
            $get['time'], $get['email']);

        if ($get['sign'] != $signHost)
            return $this->error('Signature not valid');

        $user = (new User())->selectByEmail($get['email']);
        if (!$user->row['id'])
            if (!$user->insert($partner->row['id'], $get['name'], $get['email']))
                return $this->error('Error registering new user');

        if (!$login->deleteByUserId($user->row['id']))
            return $this->error('Error deleting last session');

        if (!$login->insert(['userId' => $user->row['id'],
            'partnerId' => $partner->row['id']]))
            return $this->error('Error inserting token');

        return $this->answer([
            'token' => $login->row['token'],
            'userId' => $user->row['id'],
            'partnerInfo' => $partner->row['info']
        ]);
    }
}