<?php
$pageTitle = "Home";
include "layout.php"; 

// Check if the user is logged in (you might have a different session variable, adjust accordingly)

include "dbconnect.php";
if (!isset($_SESSION['user_id'])) { 
    // Redirect to the login page or handle unauthorized access as needed
    header("Location: login.php"); // Replace 'login.php' with your login page
    exit();
}

//$tasks = []; // Initialize $rows to null
$user_id = $_SESSION['user_id'];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['task'])) {
    $task = $_GET['task'];
    // $user_id = $_SESSION['user_id']; // Get user_id from the session

    // Input validation (add more as needed)
    if (empty($task)) { 
        echo "error";
    } else {
        // Establish a database connection (replace with your connection code)

        // Perform the database insert
        $sql = "INSERT INTO active_task (task, User_id) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("ss", $task, $user_id);
            // if ($stmt->execute()) {
            //     // Task added successfully
            //     echo "success";
            // } else {
            //     // Database error
            //     echo "error";
            // }
            $stmt->close();
        } else {
            // Database error
            echo "error";
        }

        // $query = "SELECT task FROM active_task WHERE User_id = ?";
        // $stmt_1 = $conn->prepare($query);
        // // $tasks = [];
        // if ($stmt_1) {
        //     $stmt_1->bind_param("s", $user_id);
        //     $stmt_1->execute();
        //     $tasks = $stmt_1->get_result();
            // $tasks = $result->fetch_all(MYSQLI_ASSOC);


        //     $tasks = [];
        //     if (!empty($rows)) {
        //         foreach ($rows as $row) {
        //             $tasks[] = $row["task"];
        //         }
        // }

        }
    }

    $query = "SELECT task FROM active_task WHERE User_id = ?";
    $stmt_1 = $conn->prepare($query);
        // $tasks = [];
    if ($stmt_1) {
        $stmt_1->bind_param("s", $user_id);
        $stmt_1->execute();
        $tasks = $stmt_1->get_result();
    }
$conn->close();
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
        <form action="" method="POST">
            <h2>Add New Task</h2>
            <label for="task">Task:</label>
            <input type="text" id="task" name="task" class="form-control">
            <br>
            <input class="btn btn-primary" type="submit" value="Submit">
        </form>
    </div>
    <div class="tasklist">
        <ul id="task-list">
            <?php
                while($rows=$tasks->fetch_assoc())
                {
            ?>
                    <li> <span class='dot'></span> <div class='task_name'><?php echo $rows["task"]; ?></div> </li>
            <?php
                }
            ?>
        </ul>
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
        
      
        // Embed tasks directly into JavaScript
        


        // Function to add a task to the task list
        function addTaskToList(task) {
            var taskList = document.getElementById("task-list");
            var li = document.createElement("li");
            li.innerHTML = `<span class="dot"></span> <div class="task_name">${task}</div>`;
            taskList.appendChild(li);
        }

        // Initial tasks loaded from PHP
        

    

        // Add a click event listener to the circle
        // Add a click event listener to the circle
var circle = document.querySelector("#container");
circle.addEventListener("click", function (event) {
    showPopup();
    event.stopPropagation(); // Prevent event propagation to the document
});

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

        

       // Function to fetch tasks and update the task list
        function fetchTasks() {
            fetch('get_tasks.php')
                .then(response => response.json())
                .then(data => {
                    const taskList = document.getElementById('task-list');
                    taskList.innerHTML = ''; // Clear the current list
                
                    data.tasks.forEach(task => {
                        const li = document.createElement('li');
                        li.innerHTML = `<span class="dot"></span> <div class="task_name">${task}</div>`;
                        taskList.appendChild(li);
                   });
               })
               .catch(error => {
                   console.error('Error fetching tasks:', error);
               });
        }

        // Call the fetchTasks function to load tasks when the page loads
        fetchTasks();

        // Update your submit event listener to add a new task using AJAX
        taskForm.addEventListener('submit', function (e) {
            e.preventDefault(); // Prevent form submission

            const taskInput = document.getElementById('task');
            const task_ = taskInput.value.trim();

            if (task_ === '') {
                return; // Don't add empty tasks
            }

            // Send the new task to the server using AJAX
            fetch('add_task.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
               body: `task=${task_}`,
            })
                .then(response => response.text())
                .then(response => {
                    if (response === 'success') {
                        addTaskToList(task);
                        taskInput.value = ''; // Clear the input field
                        fetchTasks(); // Fetch and update the task list
                    } else {
                        console.error('Failed to add the task.');
                    }
                })
                .catch(error => {
                    console.error('Error adding task:', error);
                });
        });


    </script>

<?php
    include "ending.php";
?>