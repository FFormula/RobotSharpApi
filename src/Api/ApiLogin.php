<?php

namespace FFormula\RobotSharpApi\Api;

use FFormula\RobotSharpApi\Model\Login;
use FFormula\RobotSharpApi\Model\Partner;
use FFormula\RobotSharpApi\Model\User;

/**
 * Class ApiLogin - Регистрация и подключение пользователей
 * @package FFormula\RobotSharpApi\Api
 */
class ApiLogin extends Api
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
     * @return array
     *      token - сгенерированный token для подключения
     *      userId - номер нового/подключённого пользователя
     * @throws \Exception - в случае любой ошибки
     */
    public function getUserToken(array $get) : array
    {
        if (!$get['partner'])
            throw new \Exception('partner not specified');

        if (!$get['email'])
            throw new \Exception('email not specified');

        if (!$get['time'])
            throw new \Exception('time not specified');

        if (!$get['sign'])
            throw new \Exception('sign not specified');

        $partner = (new Partner())->selectByName($get['partner']);

        if (!$partner->row['id'])
            throw new \Exception('partner not found');

        if ($partner->row['status'] != '1')
            throw new \Exception('partner disabled');

        $login = new Login();

        if ($login->isTimeExpired($get['time']))
            throw new \Exception('link time expired');

        $signHost = $login->getSign($partner->row['name'], $partner->row['apikey'],
            $get['time'], $get['email']);

        if ($get['sign'] != $signHost)
            throw new \Exception('Signature not valid');

        $user = (new User())->selectByEmail($get['email']);
        if (!$user->row['id'])
            if (!$user->insert($partner->row['id'], $get['name'], $get['email']))
                throw new \Exception('Error registering new user');

        if (!$login->deleteByUserId($user->row['id']))
            throw new \Exception('Error deleting last session');

        if (!$login->insert(['userId' => $user->row['id'],
            'partnerId' => $partner->row['id']]))
            throw new \Exception('Error inserting token');

        return [
            'token' => $login->row['token'],
            'userId' => $user->row['id'],
            'partnerInfo' => $partner->row['info']
        ];
    }
}