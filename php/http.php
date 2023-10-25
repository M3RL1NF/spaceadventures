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
        $response = array('message' => 'Missions retrieved successfully', 'success' => true, 'visible' => false, 'data' => $missions);
        echo json_encode($response);
        http_response_code(200);
    } elseif ($action === 'fetch') {
        file_put_contents(MISSIONS_FILE_PATH, '');
        require_once('data_check.php');
        $data = file_get_contents(MISSIONS_FILE_PATH);
        $response = array('message' => 'Missions fetched successfully', 'success' => true, 'visible' => true, 'data' => $data);
        echo json_encode($response);
        http_response_code(200);
    } else {
        $response = array('message' => 'Invalid action', 'success' => false, 'visible' => true);
        echo json_encode($response);
        http_response_code(400);
    }
}

// handle post requests
function postRequest() {
    $data = json_decode(file_get_contents('php://input'), true);
    $result = createMission($data);
    if ($result['success']) {
        $response = array('message' => 'Mission created successfully', 'success' => true, 'data' => $result);
        echo json_encode($response);
        http_response_code(200);
    } else {
        $response = array('message' => 'Failed to create mission', 'success' => false);
        echo json_encode($response);
        http_response_code(400);
    }
}

// handle put requests
function putRequest() {
    $data = json_decode(file_get_contents('php://input'), true);
    $result = updateMission($data);
    if ($result['success']) {
        $response = array('message' => 'Mission updated successfully', 'success' => true, 'data' => $result);
        echo json_encode($response);
        http_response_code(200);
    } else {
        $response = array('message' => 'Failed to update mission', 'success' => false);
        echo json_encode($response);
        http_response_code(400);
    }
}

// handle delete requests
function deleteRequest() {
    if (isset($_GET['uuid'])) {
        $id = $_GET['uuid'];
        $result = deleteMission($id);
        $message = 'Mission deleted successfully';
    } else {
        $result = deleteMissions();
        $message = 'Missions deleted successfully';
    }
    $response = array('message' => $message, 'success' => true);
    echo json_encode($response);
    http_response_code(200);
}

?>