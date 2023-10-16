<?php

// class for fetching data from target API
require_once('config.php');

// fetch data from target API
function fetchDataFromAPI() {
    $data = file_get_contents(API_URL);
    
    if ($data === false) {
        return false;
    }

    $missions = json_decode($data, true);

    if ($missions === null) {
        return false;
    }

    $filePath = MISSIONS_FILE_PATH;
    $clearFileSuccess = file_put_contents($filePath, '');

    if ($clearFileSuccess === false) {
        return false;
    }

    $modifiedMissions = modifyMissionsData($missions);
    $missionsJson = json_encode($modifiedMissions, JSON_PRETTY_PRINT);

    if ($missionsJson === false) {
        return false;
    }

    return file_put_contents($filePath, $missionsJson);
}

// modify incoming json
function modifyMissionsData($missions) {
    return array_map(function ($mission) {
        $uuid = uniqid();
        
        return [
            'uuid' => $uuid,
            'name' => $mission['mission_name'],
            'id' => $mission['mission_id'],
            'manufacturers' => $mission['manufacturers'],
            'payload' => $mission['payload_ids'],
            'description' => $mission['description']
        ];
    }, $missions);
}

?>