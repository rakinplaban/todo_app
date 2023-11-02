<?php
    $pageTitle = "Home";
    include "layout.php"; 
?>


<?php
// Check if the user is logged in (you might have a different session variable, adjust accordingly)
session_start();
if (!isset($_SESSION['user_id'])) {
    // Redirect to the login page or handle unauthorized access as needed
    header("Location: login.php"); // Replace 'login.php' with your login page
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $task = $_POST['task'];
    $user_id = $_SESSION['user_id']; // Get user_id from the session

    // Input validation (add more as needed)
    if (empty($task)) {
        echo "error";
    } else {
        // Establish a database connection (replace with your connection code)
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "todo";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Perform the database insert
        $sql = "INSERT INTO active_task (task,User_id) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("ss", $task, $user_id);
            if ($stmt->execute()) {
                // Task added successfully
                echo "success";
            } else {
                // Database error
                echo "error";
            }
            $stmt->close();
        } else {
            // Database error
            echo "error";
        }

        $conn->close();
    }
}
?>



    <div class="sidebar">
        <a class="active" href="#active">Add Task</a>
        <a href="completed.php">Completed</a>
        <a href="logout.php">Logout</a>
    </div>

    <div class="container" id = "container">
        <div class="circle" id = "circle">
            <div class="plus">
                <div class="horizontal-line"></div>
                <div class="vertical-line"></div>
            </div>
        </div>
    </div>

    <div class="popup" id="popup">
        <form action="">
            <h2>Add New Task</h2>
            <label for="task">Task:</label>
            <input type="text" id="task" name="task" class="form-control">
            <br>
            <input class="btn btn-primary" type="submit" value="Submit">
        </form>
    </div>

    <script>
        document.getElementById("circle").addEventListener("click", function(event) {
            showPopup();
            event.stopPropagation();
        });

        document.getElementById("container").addEventListener("click", function(event) {
            showPopup();
            event.stopPropagation();
        });
        // Function to open the popup
        function openPopup() {
            var popup = document.getElementById("popup");
            popup.style.display = "block";
        }

        // Function to close the popup
        function closePopup() {
            var popup = document.getElementById("popup");
            popup.style.display = "none";
        }

        // Function to add a task to the task list
        function addTaskToList(task) {
            var taskList = document.getElementById("task-list");
            var li = document.createElement("li");
            li.textContent = task;
            taskList.appendChild(li);
        }

        // Add a click event listener to the circle
        var circle = document.getElementById("container");
        circle.addEventListener("click", openPopup);

        function showPopup() {
            var popup = document.getElementById("popup");
            popup.style.display = "block";

            // Add a click event to the document to close the popup when clicking anywhere outside
            document.addEventListener("click", function closePopup(event) {
                if (event.target !== popup && !popup.contains(event.target)) {
                    hidePopup();
                    document.removeEventListener("click", closePopup);
                }
            });
        }

        function hidePopup() {
            var popup = document.getElementById("popup");
            popup.style.display = "none";
        }

        // Add a submit event listener to the task form
        var taskForm = document.getElementById("task-form");
        taskForm.addEventListener("submit", function (e) {
            e.preventDefault(); // Prevent form submission

            var taskInput = document.getElementById("task-input");
            var task = taskInput.value.trim();

            // Send the task to the server using AJAX
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "add_task.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    var response = xhr.responseText;
                    if (response === "success") {
                        addTaskToList(task);
                        taskInput.value = ""; // Clear the input field
                    }
                }
            };
            xhr.send("task=" + task);
        });
    </script>

<?php
    include "ending.php";
?>