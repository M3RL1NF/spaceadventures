<?php

// class for server side input validation
function validateInputFields($obj) {
    $errors = [];

    $name = trim($obj['name'] ?? '');
    $id = trim($obj['id'] ?? '');
    $manufacturers = $obj['manufacturers'] ?? [];
    $payload = trim($obj['payload'] ?? '');
    $description = trim($obj['description'] ?? '');

    $isValid = checkIfNotEmpty($name, $id, $payload, $description, $errors);
    $isValid = checkIdFormat($id, $errors, $isValid);
    $isValid = checkManufacturers($manufacturers, $errors, $isValid);

    return ['isValid' => $isValid, 'errors' => $errors];
}

// all fields must not be empty
function checkIfNotEmpty($name, $id, $payload, $description, &$errors) {
    if (empty($name) || empty($id) || empty($payload) || empty($description)) {
        $errors[] = "All fields must be filled out.";
        return false;
    }
    return true;
}

// the id field must contain 7 alphanumeric characters
function checkIdFormat($id, &$errors, $isValid) {
    if (!empty($id) && !preg_match('/^[0-9a-zA-Z]{7}$/', $id)) {
        $errors[] = "Mission ID must be 7 alphanumeric characters.";
        return false;
    }
    return $isValid;
}

// the manufacturer field must not be empty (array check)
function checkManufacturers($manufacturers, &$errors, $isValid) {
    if (in_array('', array_map('trim', $manufacturers))) {
        $errors[] = "Manufacturers array should not contain empty strings.";
        return false;
    }
    return $isValid;
}

?>
