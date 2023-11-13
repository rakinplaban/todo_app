<?php
    include "dbconnect.php";

    if (!isset($_SESSION['user_id'])) { 
        // Handle unauthorized access as needed
        header("HTTP/1.1 401 Unauthorized");
        exit();
    }

    $user_id = $_SESSION['user_id'];

    $query = "SELECT id, task FROM active_task WHERE User_id = ? AND completion_state = ?";
    $stmt = $conn->prepare($query);

    $completion_state = false;

    if ($stmt) {
        $stmt->bind_param("si", $user_id, $completion_state);
        $stmt->execute();
        $result = $stmt->get_result();
        $tasks = $result->fetch_all(MYSQLI_ASSOC);

        echo json_encode(['tasks' => $tasks]);
    } else {
        // Handle database error
        header("HTTP/1.1 500 Internal Server Error");
        exit();
    }

    $stmt->close();
    $conn->close();
?>
