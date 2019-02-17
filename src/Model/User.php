<?php

namespace FFormula\RobotSharpApi\Model;

/**
 * Class User - Работа с таблицей User
 * @package FFormula\RobotSharpApi\Model
 */
class User extends Record
{
    /**
     * @param string $userId
     * @return User
     * @throws \Exception
     */
    public function selectById(string $userId) : User
    {
        $this->row = $this->db->select1Row('
            SELECT id, partnerId, name, email, status
              FROM user
             WHERE id = ?', [$userId]);
        return $this;
    }

    /**
     * @param string $email
     * @return User
     * @throws \Exception
     */
    public function selectByEmail(string $email) : User
    {
        $this->row = $this->db->select1Row('
            SELECT id, partnerId, name, email, status
              FROM user
             WHERE email = ?', [$email]);
        return $this;
    }

    /**
     * @param string $partnerId
     * @param string $name
     * @param string $email
     * @return bool
     * @throws \Exception
     */
    public function insert(string $partnerId, string $name, string $email) : bool
    {
        $this->row = [
            'partnerId' => $partnerId,
            'name' => $name,
            'email' => $email,
            'status' => 'user'
        ];
        if (!$this->db->execute('
       INSERT INTO user
               SET partnerId = :partnerId,
                   name = :name,
                   email = :email,
                   status = :status', $this->row))
            return false;
        $this->row['id'] = $this->db->getLastInsertId();
        return true;
    }
}