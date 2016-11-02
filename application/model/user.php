<?php

class User
{
    /**
     * @param object $db A PDO database connection
     */
    function __construct($db)
    {
        try {
            $this->db = $db;
        } catch (PDOException $e) {
            exit('Database connection could not be established.');
        }
    }

    public function getAllUsers()
    {
        $sql = "SELECT * FROM user";
        $query = $this->db->prepare($sql);
        $query->execute();

        return $query->fetchAll();
    }

    public function insertUser($username)
    {
        $sql = "INSERT INTO user (username) VALUES (:username)";
        $query = $this->db->prepare($sql);
        $parameters = array(':username' => $username);

        $query->execute($parameters);

        return $this->getUserByUsername($username);
    }

    public function getUserByUsername($username)
    {
        $sql = "SELECT * FROM user WHERE username = :username LIMIT 1";
        $query = $this->db->prepare($sql);
        $parameters = array(':username' => $username);

        $query->execute($parameters);

        return $query->fetch();
    }

    public function getUserById($id)
    {
        $sql = "SELECT * FROM user WHERE id = :id LIMIT 1";
        $query = $this->db->prepare($sql);
        $parameters = array(':id' => $id);

        $query->execute($parameters);

        return $query->fetch();
    }

    public function resetAPIKey($id)
    {
        $newApiKey = substr(str_shuffle(MD5(microtime())), 0, 30);

        $sql = "UPDATE user SET api_key = :api_key WHERE id = :id";
        $query = $this->db->prepare($sql);
        $parameters = array(':api_key' => $newApiKey, ':id' => $id);

        $query->execute($parameters);

        return $newApiKey;
    }

    public function truncate()
    {
        $sql = "DELETE FROM user";
        $parameters = array();
        $query = $this->db->prepare($sql);
        return $query->execute($parameters);
    }
}
