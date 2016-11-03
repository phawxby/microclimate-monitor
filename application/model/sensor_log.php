<?php

class Sensor_log
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

    public function getLogsForSensor($sensor_id)
    {
        $sql = "SELECT * FROM sensor_log WHERE sensor_id = :sensor_id";
        $query = $this->db->prepare($sql);
        $parameters = array(':sensor_id' => $user_id);
        $query->execute($parameters);

        return $query->fetchAll();
    }

    public function insertLog($sensor_id, $value, $date = null)
    {
        if (!isset($date) || is_null($date)) {
            $date = gmdate("c");
        }

        $sql = "INSERT INTO sensor_log (sensor_id, date, value) VALUES (:sensor_id, :date, :value)";
        $query = $this->db->prepare($sql);
        $parameters = array(':sensor_id' => $sensor_id, ':date' => $date, ':value' => $value);

        return $query->execute($parameters);
    }

    public function truncate()
    {
        $sql = "DELETE FROM sensor_log";
        $parameters = array();
        $query = $this->db->prepare($sql);
        return $query->execute($parameters);
    }
}
