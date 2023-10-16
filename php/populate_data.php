<?php

// fallback class in case target API fails
require_once('config.php');

// populate json-file with test data
function populateWithTestData() {
    $testMissions = generateTestMissions();
    $filePath = MISSIONS_FILE_PATH;
    
    if (!file_put_contents($filePath, '')) {
        return false;
    }

    $missionsData = json_encode($testMissions, JSON_PRETTY_PRINT);

    if ($missionsData === false) {
        return false;
    }

    return file_put_contents($filePath, $missionsData);
}

// generate test missions
function generateTestMissions() {
    return [
        [
            "uuid" => "c5c5a64c-3c78-4e21-880a-3f3c563cd7d6",
            "name" => "Mission 1",
            "id" => "9D1B7E0",
            "manufacturers" => ["Manufacturer A", "Manufacturer B"],
            "payload" => "Payload 1",
            "description" => "Description for Mission 1"
        ],
        [
            "uuid" => "f47ac10b-58cc-4372-a567-0e02b2c3d479",
            "name" => "Mission 2",
            "id" => "9D1B7E1",
            "manufacturers" => ["Manufacturer C"],
            "payload" => "Payload 2",
            "description" => "Description for Mission 2"
        ],
        [
            "uuid" => "6ba7b810-9dad-11d1-80b4-00c04fd430c8",
            "name" => "Mission 3",
            "id" => "9D1B7E2",
            "manufacturers" => ["Manufacturer D"],
            "payload" => "Payload 3",
            "description" => "Description for Mission 3"
        ]
    ];
}

?>