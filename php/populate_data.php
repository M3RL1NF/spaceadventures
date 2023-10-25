<?php

// fallback class in case target API fails
require_once('config.php');

// populate json-file with test data
function populateWithTestData() {
    $testMissions = generateTestMissions();
    $filePath = MISSIONS_FILE_PATH;

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
            "name" => "Thaicom",
            "id" => "9D1B7E0",
            "manufacturers" => ["Orbital ATK"],
            "payload" => ["Thaicom 6"],
            "description" => "Thaicom is the name of a series of communications satellites operated from Thailand"
        ],
        [
            "uuid" => "f47ac10b-58cc-4372-a567-0e02b2c3d479",
            "name" => "Telstar",
            "id" => "9D1B7E1",
            "manufacturers" => ["SSL"],
            "payload" => ["Telstar 19V"],
            "description" => "Telstar 19V is a communication satellite of the Canadian satellite communications company Telesat"
        ],
        [
            "uuid" => "6ba7b810-9dad-11d1-80b4-00c04fd430c8",
            "name" => "Iridium NEXT",
            "id" => "9D1B7E2",
            "manufacturers" => ["Iridium NEXT 1"],
            "payload" => ["Payload 3"],
            "description" => "Iridium NEXT is the second-generation worldwide network of telecommunications satellites"
        ]
    ];
}

?>