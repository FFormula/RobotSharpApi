<?php
namespace FFormula\RobotSharpApi\Model;

/**
 * Class Login - Работа с таблицей Login
 * @package FFormula\RobotSharpApi\Model
 */
class Login extends Record
{
    /**
     * @param string $token
     * @return Login
     * @throws \Exception
     */
    public function selectByToken(string $token) : Login
    {
        $this->deleteExpired();
        $this->row = $this->db->select1Row('
            SELECT token, partnerId, userId, expired
              FROM login
             WHERE token = ?', [ $token ]);
        return $this;
    }

    /**
     * @param int $timeUser
     * @return bool
     */
    public function isTimeExpired(int $timeUser) : bool
    {
        $timeHost = time();
        return abs($timeUser - $timeHost) > 24*3600;
    }

    /**
     * @param string $partnerName
     * @param string $apikey
     * @param string $time
     * @param string $email
     * @return string
     */
    public function getSign(string $partnerName, string $apikey, string $time, string $email) : string
    {
        return md5($partnerName . '/' . $apikey . '/' . $time . '/' . $email);
    }

    /**
     * @param array $row
     * @return bool
     * @throws \Exception
     */
    public function insert(array $row) : bool
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

    /**
     * @throws \Exception
     */
    public function deleteExpired() : void
    {
        $this->db->execute('
            DELETE FROM login 
             WHERE expired < ?', [time()]);
    }

    /**
     * @param string $userId
     * @return bool
     * @throws \Exception
     */
    public function deleteByUserId(string $userId) : bool
    {
        return $this->db->execute('
            DELETE FROM login
             WHERE userId = ?', [$userId]);
    }

    /**
     * @return string
     */
    private function getRandomString() : string
    {
        return substr(str_shuffle(
            '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'),
            0, 32);
    }

}