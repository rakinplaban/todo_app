<?php
    // Adding task on frontend.
    include 'dbconnect.php';
    $sql = "SELECT task FROM active_task WHERE User_id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
           $stmt->bind_param("s", $user_id);
           $stmt->execute();
           $result = $stmt->get_result();
           $tasks = array();

           while ($row = $result->fetch_assoc()) {
               $tasks[] = $row['task'];
           }

           // Return tasks as JSON
           echo json_encode(array('tasks' => $tasks));
       } else {
           http_response_code(500); // Internal Server Error
       }
       $stmt->close();
       $conn->close();

?>