<?php
include_once "include/conndb.php";

// Handle HTTP requests
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        if (isset($_GET['id'])) {
            $id = intval($_GET['id']);
            getPerson($conn, $id);
        } else {
            getPersons($conn);
        }
        break;
    case 'POST':
        $data = json_decode(file_get_contents("php://input"));
        createPerson($conn, $data);
        break;
    case 'PUT':
        if (isset($_GET['id'])) {
            $id = intval($_GET['id']);
            $data = json_decode(file_get_contents("php://input"));
            updatePerson($conn, $id, $data);
        } else {
            header("HTTP/1.0 400 Bad Request");
            echo "Missing 'id' parameter.";
        }
        break;
    case 'DELETE':
        if (isset($_GET['id'])) {
            $id = intval($_GET['id']);
            deletePerson($conn, $id);
        } else {
            header("HTTP/1.0 400 Bad Request");
            echo "Missing 'id' parameter.";
        }
        break;
    default:
        header("HTTP/1.0 405 Method Not Allowed");
        break;
}

function getPersons($conn) {
    $result = $conn->query("SELECT * FROM persons");
    if (!$result) {
        header("HTTP/1.0 500 Internal Server Error");
        echo "Error: " . $conn->error;
        return;
    }
    
    $rows = array();
    while ($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }
    header('Content-Type: application/json');
    echo json_encode($rows);
}

function getPerson($conn, $id) {
    $stmt = $conn->prepare("SELECT * FROM persons WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if (!$result) {
        header("HTTP/1.0 500 Internal Server Error");
        echo "Error: " . $conn->error;
        return;
    }
    
    $row = $result->fetch_assoc();
    header('Content-Type: application/json');
    echo json_encode($row);
}

function createPerson($conn, $data) {
    $name = $data->name;
    $age = intval($data->age);
    $email = $data->email;
    
    // Validations on name, age and email
    if (empty($name) || !is_string($name)) {
        header("HTTP/1.0 400 Bad Request");
        echo "Invalid 'name' parameter.";
        return;
    }   elseif (empty($age) || !is_int($age)) {
        header("HTTP/1.0 400 Bad Request");
        echo "Invalid 'age' parameter.";
    }   elseif (empty($email) || !validateEmail($email)) {
        header("HTTP/1.0 400 Bad Request");
        echo "Invalid 'email' parameter.";
    }
    
    
    $stmt = $conn->prepare("INSERT INTO persons (name, age, email) VALUES (?, ?, ?)");
    $stmt->bind_param("sis", $name, $age, $email);
    $stmt->execute();
    
    if ($stmt->affected_rows === 1) {
        header("HTTP/1.0 201 Created");
    } else {
        header("HTTP/1.0 500 Internal Server Error");
        echo "Error: " . $stmt->error;
    }
}

function updatePerson($conn, $id, $data) {
    $name = $data->name;
    $age = intval($data->age);
    $email = $data->email;

    // Validations on name, age, and email
    if (empty($name) || !is_string($name)) {
        header("HTTP/1.0 400 Bad Request");
        echo "Invalid 'name' parameter.";
        return;
    } elseif (empty($age) || !is_int($age)) {
        header("HTTP/1.0 400 Bad Request");
        echo "Invalid 'age' parameter.";
        return;
    } elseif (empty($email) || !validateEmail($email)) {
        header("HTTP/1.0 400 Bad Request");
        echo "Invalid 'email' parameter.";
        return;
    }

    $stmt = $conn->prepare("UPDATE persons SET name = ?, age = ?, email = ? WHERE id = ?");
    $stmt->bind_param("sisi", $name, $age, $email, $id);
    $stmt->execute();

    if ($stmt->affected_rows === 1) {
        header("HTTP/1.0 200 OK");
    } else {
        header("HTTP/1.0 500 Internal Server Error");
        echo "Error: " . $stmt->error;
    }
}

function deletePerson($conn, $id) {
    $stmt = $conn->prepare("DELETE FROM persons WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    
    if ($stmt->affected_rows === 1) {
        header("HTTP/1.0 204 No Content");
    } else {
        header("HTTP/1.0 500 Internal Server Error");
        echo "Error: " . $stmt->error;
    }
}

function validateEmail($email) {
    // Define a regular expression pattern for a valid email address
    $pattern = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';

    // Use the preg_match function to check if the email matches the pattern
    if (preg_match($pattern, $email)) {
        return true; // Valid email
    } else {
        return false; // Invalid email
    }
}


$conn->close();
?>
