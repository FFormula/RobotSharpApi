<?php

namespace FFormula\RobotSharp\API;

use FFormula\RobotSharp\Model\Login;
use FFormula\RobotSharp\Model\Partner;
use FFormula\RobotSharp\Model\User;

class Auth extends Api
{
    /**
     * @param $param array - user login info:
     *      partner
     *      email
     *      time
     *      sign
     * @return string - generated token
     *      token - login access token if correct
     *      userId - joined/logged userId
     */
    public function login(array $param) : string
    {
        if (!$param['partner'])
            return $this->error('partner not specified');

        if (!$param['email'])
            return $this->error('email not specified');

        if (!$param['time'])
            return $this->error('time not specified');

        if (!$param['sign'])
            return $this->error('sign not specified');

        $partner = (new Partner())->selectByName($param['partner']);

        if (!$partner->row['id'])
            return $this->error('partner not found');

        if ($partner->row['status'] != '1')
            return $this->error('partner disabled');

        $login = new Login();

        if ($login->isTimeExpired($param['time']))
            return $this->error('link time expired');


        $signHost = $login->getSign($partner->row['name'], $partner->row['apikey'],
                                    $param['time'], $param['email']);

        if ($param['sign'] != $signHost)
            return $this->error('Signature not valid');

        $user = (new User())->selectByEmail($param['email']);
        if (!$user->row['id'])
            if (!$user->insert(['partnerId' => $partner->row['id'],
                                'name' => $param['name'],
                                'email' => $param['email'],
                                'status' => 'user']))
                return $this->error('Error registering new user');

        if (!$login->deleteByUserId($user->row['id']))
            return $this->error('Error deleting last session');

        if (!$login->insert(['userId' => $user->row['id'],
                             'partnerId' => $partner->row['id']]))
            return $this->error('Error inserting token');

        return $this->answer([
            'token' => $login->row['token'],
            'userId' => $user->row['id']
        ]);
    }

    public function logout($param) : string
    {

    }
}