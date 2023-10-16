<?php

// class for crud-method handling
require_once('config.php');
require_once('validate.php');

$missionsFile = MISSIONS_FILE_PATH;

// create
function createMission($mission) {
    $validationResult = validateInputFields($mission);
    if (!$validationResult['isValid']) {
        return $validationResult;
    }
    $missions = getAllMissions();
    $missions[] = $mission;
    saveMissions($missions);
    return array('success' => true);
}

// read
function getAllMissions() {
    global $missionsFile;
    if (file_exists($missionsFile)) {
        $missionsData = file_get_contents($missionsFile);
        return json_decode($missionsData, true);
    } else {
        return array();
    }
}

// update
function updateMission($updatedMission) {
    $validationResult = validateInputFields($updatedMission);
    if (!$validationResult['isValid']) {
        return $validationResult;
    }
    $missions = getAllMissions();
    $updated = false;
    foreach ($missions as $key => $mission) {
        if ($mission['uuid'] == $updatedMission['uuid']) {
            $missions[$key] = $updatedMission;
            $updated = true;
            break;
        }
    }
    if ($updated) {
        saveMissions($missions);
        return array('success' => true);
    } else {
        return array('success' => false, 'errors' => ["Mission not found."]);
    }
}

// delete
function deleteMission($id) {
    $missions = getAllMissions();
    $deleted = false;
    foreach ($missions as $key => $mission) {
        if ($mission['uuid'] == $id) {
            unset($missions[$key]);
            $deleted = true;
            break;
        }
    }
    if ($deleted) {
        saveMissions($missions);
        if (count($missions) == 0) {
            file_put_contents(MISSIONS_FILE_PATH, '');
        }
        return true;
    } else {
        return false;
    }
}

// delete
function deleteMissions() {
    global $missionsFile;
    file_put_contents($missionsFile, '');
    return true;
}

// save
function saveMissions($missions) {
    global $missionsFile;
    $missionsJson = json_encode($missions, JSON_PRETTY_PRINT);
    if ($missionsJson !== false) {
        file_put_contents($missionsFile, $missionsJson);
    }
}

?>
