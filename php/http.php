<?php

// class for handling hppt-requests
require_once('storage.php');
require_once('validate.php');

$httpMethod = $_SERVER['REQUEST_METHOD'];

switch ($httpMethod) {
    case 'POST':
        postRequest();
        break;
    case 'GET':
        getRequest();
        break;
    case 'PUT':
        putRequest();
        break;
    case 'DELETE':
        deleteRequest();
        break;
    default:
        http_response_code(405);
        break;
}

// handle get requests
function getRequest() {
    $action = $_GET['action'] ?? '';
    if ($action === 'get') {
        $missions = getAllMissions();
        echo json_encode($missions);
        http_response_code(200);
    } elseif ($action === 'fetch') {
        file_put_contents(MISSIONS_FILE_PATH, '');
        require_once('data_check.php');
        echo file_get_contents(MISSIONS_FILE_PATH);
        http_response_code(200);
    } else {
        http_response_code(400);
    }
}

// handle post requests
function postRequest() {
    $data = json_decode(file_get_contents('php://input'), true);
    $result = createMission($data);
    if ($result['success']) {
        echo json_encode(['success' => true, 'message' => 'Mission created successfully']);
        http_response_code(200);
    } else {
        echo json_encode(['success' => false, 'errors' => $result['errors']]);
        http_response_code(400);
    }
}

// handle put requests
function putRequest() {
    $data = json_decode(file_get_contents('php://input'), true);
    $result = updateMission($data);
    if (isset($result['success']) && $result['success']) {
        echo json_encode(['success' => true, 'message' => 'Mission updated successfully']);
        http_response_code(200);
    } else {
        echo json_encode(['success' => false, 'errors' => $result['errors']]);
        http_response_code(400);
    }
}

// handle delete requests
function deleteRequest() {
    if (isset($_GET['uuid'])) {
        $id = $_GET['uuid'];
        deleteMission($id);
    } else {
        deleteMissions();
    }
    http_response_code(200);
}

?>
