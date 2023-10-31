<?php
    $pageTitle = "Home";
    include "layout.php"; 
?>
    <div class="sidebar">
        <a class="active" href="#active">Add Task</a>
        <a href="completed.php">Completed</a>
    </div>

    <div class="container">
        <div class="circle">
            <div class="plus">
                <div class="horizontal-line"></div>
                <div class="vertical-line"></div>
            </div>
        </div>
    </div>
<?php
    include "ending.php";
?>