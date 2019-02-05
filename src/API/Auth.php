<?php

namespace FFormula\RobotSharp\API;

use FFormula\RobotSharp\Model\Login;
use FFormula\RobotSharp\Model\Partner;
use FFormula\RobotSharp\Model\User;

class Auth extends Api
{
    /**
     * @param $get array - user login info:
     *      partner
     *      email
     *      time
     *      sign
     * @return string - generated token
     *      token - login access token if correct
     *      userId - joined/logged userId
     */
    public function login(array $get) : string
    {
        try
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
                if (!$user->insert(['partnerId' => $partner->row['id'],
                    'name' => $get['name'],
                    'email' => $get['email'],
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
        catch (\Exception $ex)
        {
            return $this->exception($ex);
        }
    }

    public function logout($get) : string
    {

    }
}