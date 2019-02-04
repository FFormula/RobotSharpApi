<?php
namespace FFormula\RobotSharp\Model;

class Login extends Record
{

    public function isTimeExpired($timeUser) : bool
    {
        $timeHost = time();
        return abs($timeUser - $timeHost) > 24*3600;
    }

    public function getSign($partnerName, $apikey, $time, $email) : string
    {
        return md5($partnerName . '/' .
            $apikey . '/' .
            $time . '/' .
            $email);
    }

    public function insert($row) : bool
    {
        $row['token'] = $this->getRandomString();
        $row['expired'] = time() + 24*3600;
        $this->row = $row;
        return $this->db->execute('
            INSERT INTO login
               SET token = :token,
                   partnerId = :partnerId,
                   userId = :userId,
                   expired = :expired', $this->row);
    }

    public function deleteByUserId(string $userId) : bool
    {
        return $this->db->execute('
            DELETE FROM login
             WHERE userId = ?', [$userId]);
    }

    private function getRandomString() : string
    {
        return substr(str_shuffle(
            '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'),
            0, 32);
    }

}