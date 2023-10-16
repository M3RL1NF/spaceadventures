<?php

// class for handling json-file and content
require_once('fetch_data.php');
require_once('populate_data.php');
require_once('config.php');

$missionsFile = MISSIONS_FILE_PATH;

function createInitialMissionsFile($file) {
    $initialData = json_encode([], JSON_PRETTY_PRINT);
    file_put_contents($file, $initialData);
}

function ensureMissionsFileExists($file) {
    if (!file_exists($file)) {
        createInitialMissionsFile($file);
    }
}
function fetchDataIfNeeded($file) {
    if (empty(file_get_contents($file))) {
        fetchDataFromAPI();
    }
}

function populateDataIfNeeded($file) {
    if (empty(file_get_contents($file))) {
        populateWithTestData();
    }
}

ensureMissionsFileExists($missionsFile);
fetchDataIfNeeded($missionsFile);
populateDataIfNeeded($missionsFile);

?>
