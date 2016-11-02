<?php

class Climate
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

    public function getClimatesForUser($user_id)
    {
        $sql = "SELECT * FROM climate WHERE user_id = :user_id";
        $query = $this->db->prepare($sql);
        $parameters = array(':user_id' => $user_id);
        $query->execute($parameters);

        return $query->fetchAll();
    }

    public function insertClimate($user_id, $name, $short_name)
    {
        $short_name = isset($short_name) && $short_name ? $short_name : $name;
        $short_name = preg_replace("/[^A-Za-z0-9\-_]/", '', $short_name);
        $unique_short_name = $short_name;

        for($i = 0; $i <= 20; $i++) 
        {
            $unique_short_name = $short_name . ($i <= 0 ? "" : $i);
            $check = $this->getClimateByShortName($user_id, $unique_short_name);

            if (!isset($check) || !$check) {
                break;
            }
            if ($i >= 20) { 
                throw new Exception('Could not create unique short_name.');
            }
        }

        $sql = "INSERT INTO climate (user_id, name, short_name) VALUES (:user_id, :name, :short_name)";
        $query = $this->db->prepare($sql);
        $parameters = array(':user_id' => $user_id, ':name' => $name, ':short_name' => $unique_short_name);

        $query->execute($parameters);

        return $this->getClimateByShortName($user_id, $unique_short_name);
    }

    public function getClimateByShortName($user_id, $short_name)
    {
        $sql = "SELECT * FROM climate WHERE user_id = :user_id AND short_name = :short_name LIMIT 1";
        $query = $this->db->prepare($sql);
        $parameters = array(':user_id' => $user_id, ':short_name' => $short_name);

        $query->execute($parameters);

        return $query->fetch();
    }

    public function truncate()
    {
        $sql = "DELETE FROM climate";
        $parameters = array();
        $query = $this->db->prepare($sql);
        return $query->execute($parameters);
    }
}
