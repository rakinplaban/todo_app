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


    $user_id = $_SESSION['user_id'];    
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['task'])) {
        $task = $_POST['task'];

        // Input validation (add more as needed)
        if (empty($task)) { 
            echo "error";
        } else {
            // Perform the database insert
            $sql = "INSERT INTO active_task (task, User_id) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            if ($stmt) {
                $stmt->bind_param("ss", $task, $user_id);
                $stmt->close();
            } else {
                // Database error
                echo "error";
            }
        }
    }elseif (isset($_POST['taskId'])) {
        $taskId = $_POST['taskId'];

        // Update completion state
        $updateQuery = "UPDATE active_task SET completion_state = TRUE WHERE id = ?";
        $stmtUpdate = $conn->prepare($updateQuery);
        if ($stmtUpdate) {
            $stmtUpdate->bind_param("is", $taskId, $user_id);
            $stmtUpdate->execute();
            $stmtUpdate->close();
            echo "success";
        } else {
            echo "error";
        }
    }

    $query = "SELECT id, task FROM active_task WHERE User_id = ? AND completion_state = ?";
    $stmt_1 = $conn->prepare($query);
    $completion_state = FALSE;
    if ($stmt_1) {
        $stmt_1->bind_param("si", $user_id, $completion_state);
        $stmt_1->execute();
        $res = $stmt_1->get_result();
        $tasks = $res->fetch_all(MYSQLI_ASSOC);
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
        <form action="" method="POST" id="task-form">
            <h2>Add New Task</h2>
            <label for="task">Task:</label>
            <input type="text" id="task-input" name="task" class="form-control">
            <br>
            <input class="btn btn-primary" type="submit" value="Submit">
        </form>
    </div>
    <div class="tasklist">
        <ul id="task-list">
            <?php
                foreach($tasks as $task) {
            ?>
                <li data-task-id="<?php echo $task["id"]; ?>">
                    <span class='dot'></span>
                    <span class='task_name'><?php echo $task["task"] ?></span>
                </li>
            <?php
                }
            ?>
        </ul>
    </div>


    <script>
        document.querySelector("#container").addEventListener("click", function(event) {
            showPopup();
            event.stopPropagation();
        });
        
        // Function to add a task to the task list
        function addTaskToList(task) {
            var taskList = document.getElementById("task-list");
            var li = document.createElement("li");
            li.setAttribute("data-task-id", task.id);
            li.innerHTML = `<span class="dot"></span> <span class="task_name">${task}</span>`;
            taskList.appendChild(li);
        }


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
        var taskForm = document.querySelector("#task-form");

       // Function to fetch tasks and update the task list
        function fetchTasks() {
            fetch('get_tasks.php')
            .then(response => response.json())
            .then(data => {
                const taskList = document.querySelector('#task-list');
                taskList.innerHTML = ''; // Clear the current list

                if (data && data.tasks) {
                    data.tasks.forEach(task => {
                        console.log(task);
                        const li = document.createElement('li');
                        li.setAttribute("data-task-id", task.id);
                        li.innerHTML = `<span class="dot"></span> <span class="task_name">${task.task}</span>`;
                        taskList.appendChild(li);
                    });
                }
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

            const taskInput = document.querySelector('#task-input');
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
                        addTaskToList(task_);
                        taskInput.value = ''; // Clear the input field
                        hidePopup();
                        fetchTasks(); // Fetch and update the task list
                    } else {
                        console.error('Failed to add the task.');
                    }
                })
                .catch(error => {
                    console.error('Error adding task:', error);
                });
        });


        document.querySelector("#task-list").addEventListener("click", function (event) {
        const target = event.target;

        if (target.classList.contains("dot")) {
            const taskId = target.closest("li").getAttribute("data-task-id");
            console.log('Retrieved taskId:', taskId);
            // Rest of your code
            fetch('update_task.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `taskId=${taskId}`, // Include taskId in the request body
            })
                .then(response => response.text())
                .then(response => {
                    if (response === 'success') {
                        // Update UI to mark the task as completed
                        target.style.backgroundColor = 'green';
                        target.parentElement.style.opacity = '0';
                        location.reload();
                    } else {
                        console.error('Failed to update task completion status.');
                    }
                })
                .catch(error => {
                    console.error('Error updating task completion status:', error);
                });
        }
    });

    </script>

<?php
    include "ending.php";
?>