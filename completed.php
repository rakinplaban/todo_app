<?php
    $pageTitle = "Home";
    include "layout.php"; 
    include "dbconnect.php";
    if (!isset($_SESSION['user_id'])) { 
        // Redirect to the login page or handle unauthorized access as needed
        header("Location: login.php"); // Replace 'login.php' with your login page
        exit();
    }

    $user_id = $_SESSION['user_id']; 
    $query = "SELECT id, task FROM active_task WHERE User_id = ? AND completion_state = ?";
    $stmt_1 = $conn->prepare($query);
    $completion_state = TRUE;
    if ($stmt_1) {
        $stmt_1->bind_param("si", $user_id, $completion_state);
        $stmt_1->execute();
        $res = $stmt_1->get_result();
        $tasks = $res->fetch_all(MYSQLI_ASSOC);
    }
    $conn->close();
?>
    <div class="sidebar">
        <a href="index.php">Add Task</a>
        <a class="active" href="#active">Completed</a>
        <a href="logout.php">Logout</a>
    </div>

    <div class="tasklist">
        <ul id="task-list">
            <?php
                foreach($tasks as $task) {
            ?>
                <li data-task-id="<?php echo $task["id"]; ?>">
                    <span class='dot-completed'></span>
                    <span class='task_name'><?php echo $task["task"] ?></span>
                </li>
            <?php
                }
            ?>
        </ul>
    </div>
    
<?php
    include "ending.php";
?>