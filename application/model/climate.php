<?php
require_once("_helpers.php");

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

        $climates = $this->getClimatesForUser($user_id);
        $exisitingShortNames = array_map(function($o) { $o->short_name; }, $climates);
        $short_name = Helpers::makeUniqueShortName($short_name, $exisitingShortNames);

        $sql = "INSERT INTO climate (user_id, name, short_name) VALUES (:user_id, :name, :short_name)";
        $query = $this->db->prepare($sql);
        $parameters = array(':user_id' => $user_id, ':name' => $name, ':short_name' => $short_name);

        $query->execute($parameters);

        return $this->getClimateByShortName($user_id, $short_name);
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
