<?php
$pageTitle = "Registration Successful";
include "layout.php"; 
?>

<div class="entry bg-success">
    <div>
        <h2 style="text-align: center; color: aliceblue;">Registration Successful</h2>
    </div>

    <div class="bg-light border border-success">
        <div class="formview">
            <p>Your registration was successful. You can now log in using your credentials.</p>
            <div style="margin-bottom: 10px;"></div>
            <div style="margin-left: 10px;">
                Already have an account? <a href="login.php">Log In here.</a>
            </div>
        </div>
    </div>
</div>

<?php include "ending.php"; ?>
