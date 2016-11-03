<?php

class Admin extends Controller
{
    public function insertMockData()
    {
        $this->models->sensor_log->truncate();
        $this->models->sensor->truncate();
        $this->models->climate->truncate();
        $this->models->user->truncate();

        // ----------------------------------------

        $testUser = $this->models->user->insertUser("phawxby");
        $apiKey = $this->models->user->resetAPIKey($testUser->id);
        $testUser = $this->models->user->getUserById($testUser->id);

        // ----------------------------------------

        $vegPatch = $this->models->climate->insertClimate($testUser->id, "Veg Patch", "VegPatch");
        $piggieHutch = $this->models->climate->insertClimate($testUser->id, "Piggie Hutch", "PiggieHutch");

        // ----------------------------------------

        $outdoorTemp = $this->models->sensor->insertSensor($vegPatch->id, "Outdoor Temperature", Units::DegreesCelsius, "OutdoorTemp");

        // ----------------------------------------

        $this->models->sensor_log->insertLog($outdoorTemp->id, 6);

        // load views. within the views we can echo out $song easily
        require APP . 'view/_templates/header.php';
        require APP . 'view/admin/insertMockData.php';
        require APP . 'view/_templates/footer.php';
    }
}
