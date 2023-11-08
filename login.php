<?php
    include "dbconnect.php";

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Validate user input (you can add more validation as needed)
        if (empty($email) || empty($password)) {
            // Handle validation errors here
            $error_message = "Both email and password are required.";
        } else {
            // Retrieve user data from the database based on the provided email
            $sql = "SELECT id, email, password FROM user WHERE email = ?";
            $stmt = $conn->prepare($sql);
        
            if ($stmt) {
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $stmt->store_result();
            
                if ($stmt->num_rows === 1) {
                    $stmt->bind_result($user_id, $user_email, $hashed_password);
                    $stmt->fetch();
                    
                    // Verify the provided password against the hashed password
                    if (password_verify($password, $hashed_password)) {
                        // Password is correct, set user_id in session
                        $_SESSION['user_id'] = $user_id;
                        header("Location: index.php"); // Redirect to the index page or any desired page
                        exit();
                    } else {
                        // Handle incorrect password
                        $error_message = "Incorrect password. Please try again.";   
                        
                    }
                } else {
                    // Handle user not found
                    $error_message = "User not found. Please register or try a different email.";
                }
            } else {
                // Handle database error
                $error_message = "Login failed. Please try again.";
            }
        }
    }

// Rest of your code remains the same
?>

<?php 
    $pageTitle = "Login User";
    include "layout.php" 
?>
    <div class="entry bg-success">
        <div>
            <h2 style="text-align: center; color:aliceblue;">Login</h2>
        </div>
    
        <div class="bg-light border border-success">
            <div class="formview">
                <?php if (!empty($error_message)) : ?>
                    <div class="alert alert-danger" style="background-color: red; color: white;">
                        <?php echo $error_message; ?>
                    </div>
                <?php endif; ?>
                <form action="" method="post">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input autofocus class="form-control" type="email" name="email" placeholder="Email Id">
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input class="form-control" type="password" name="password" placeholder="Password">
                    </div>
                    <input class="btn btn-success" type="submit" value="Login">
                </form>
            </div>
            <div style="margin-bottom: 10px;"> </div>
            <div style="margin-left: 10px;">
                Don't have an account? <a href="register.php">Register here.</a>
            </div>
            
        </div>
        
    </div>
<?php include "ending.php" ?>