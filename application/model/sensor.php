<?php
require_once("_helpers.php");

class Units
{
    const Other = 0;

    const DegreesCelsius = 1;
    const DegreesFahrenheit = 2;
    const Kelvin = 3;

    const MetersPerSecond = 4;
    const MilesPerHour = 5;
    const KilometersPerHour = 6;

    const Angle = 7;

    const Lumen = 8;

    const Millimeters = 9;
    const Centimeters = 10;
    const Meters = 11;
    const Kilometers = 12;
}

class Sensor
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

    public function getSensorsForClimate($climate_id)
    {
        $sql = "SELECT * FROM sensor WHERE climate_id = :climate_id";
        $query = $this->db->prepare($sql);
        $parameters = array(':climate_id' => $climate_id);
        $query->execute($parameters);

        return $query->fetchAll();
    }

    public function insertSensor($climate_id, $name, $unit, $short_name)
    {
        $short_name = isset($short_name) && $short_name ? $short_name : $name;

        $sensors = $this->getSensorsForClimate($climate_id);
        $exisitingShortNames = array_map(function($o) { $o->short_name; }, $sensors);
        $short_name = Helpers::makeUniqueShortName($short_name, $exisitingShortNames);

        $sql = "INSERT INTO sensor (climate_id, name, short_name, unit) VALUES (:climate_id, :name, :short_name, :unit)";
        $query = $this->db->prepare($sql);
        $parameters = array(':climate_id' => $climate_id, ':name' => $name, ':short_name' => $short_name, ':unit' => $unit);

        $query->execute($parameters);

        return $this->getSensorByShortName($climate_id, $short_name);
    }

    public function getSensorByShortName($climate_id, $short_name)
    {
        $sql = "SELECT * FROM sensor WHERE climate_id = :climate_id AND short_name = :short_name LIMIT 1";
        $query = $this->db->prepare($sql);
        $parameters = array(':climate_id' => $climate_id, ':short_name' => $short_name);

        $query->execute($parameters);

        return $query->fetch();
    }

    public function truncate()
    {
        $sql = "DELETE FROM sensor";
        $parameters = array();
        $query = $this->db->prepare($sql);
        return $query->execute($parameters);
    }
}
