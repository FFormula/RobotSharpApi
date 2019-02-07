<?php

namespace FFormula\RobotSharpApi\Model;

class User extends Record
{
    public function selectById($userId) : User
    {
        $this->row = $this->db->select1Row('
            SELECT id, partnerId, name, email, status
              FROM user
             WHERE id = ?', [$userId]);
        return $this;
    }

    public function selectByEmail($email) : User
    {
        $this->row = $this->db->select1Row('
            SELECT id, partnerId, name, email, status
              FROM user
             WHERE email = ?', [$email]);
        return $this;
    }

    public function insert($row) : bool
    {
        $this->row = $row;
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